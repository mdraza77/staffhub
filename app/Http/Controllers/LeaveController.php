<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Leave-Index', only: ['index']),
            new Middleware('permission:Leave-Apply', only: ['store']),
            new Middleware('permission:Leave-ApproveReject', only: ['updateStatus']),
        ];
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('HR Manager')) {
            $leaves = Leave::with(['user', 'leaveType'])
                ->latest()
                ->get();
        } else {
            $leaves = Leave::where('user_id', $user->id)
                ->with(['user', 'leaveType'])
                ->latest()
                ->get();
        }

        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('leaves.index', compact('leaves', 'leaveTypes'));
    }

    // 2. Employee: Apply for a New Leave
    public function store(Request $request)
    {
        // Validate incoming form data
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // 1. Parse dates using Carbon and calculate total requested days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $requestedDays = $startDate->diffInDays($endDate) + 1;  // +1 ensures same-day leave counts as 1 day

        // 2. Fetch the selected Leave Type quota configuration
        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $maxAllowed = (float) $leaveType->days_allowed;  // Cast to float to avoid calculation type mismatch

        // 3. Calculate used/pending days for the current user and leave category in the current year
        $currentYear = Carbon::now()->year;

        $usedLeaves = (float) Leave::where('user_id', Auth::id())
            ->where('leave_type_id', $leaveType->id)
            ->whereIn('status', ['approved', 'pending'])  // Include pending to prevent double-applying
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        // 4. Calculate available remaining balance
        $availableBalance = $maxAllowed - $usedLeaves;

        // 5. Restrict application submission if requested days exceed available balance
        if ($requestedDays > $availableBalance) {
            return back()
                ->withInput()
                ->withErrors([
                    'leave_type_id' => "You are requesting {$requestedDays} days, but you only have {$availableBalance} days left for {$leaveType->name}."
                ]);
        }

        // 6. Secure data persistence using Database Transactions
        try {
            DB::beginTransaction();

            Leave::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $requestedDays,  // Store calculated range total
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            DB::commit();
            return back()->with('success', 'Leave request submitted successfully. Waiting for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave Application Database Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while applying for leave.');
        }
    }

    // 3. Admin: Approve or Reject Leave
    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_remark' => 'nullable|string|max:255',
        ]);

        try {
            $leave->update([
                'status' => $request->status,
                'admin_remark' => $request->admin_remark,
            ]);

            $message = $request->status === 'approved' ? 'Leave approved successfully.' : 'Leave rejected.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Leave Status Update Error: ' . $e->getMessage());
            return back()->with('error', 'Could not update leave status.');
        }
    }
}
