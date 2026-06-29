<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function index()
    {
        // Admin ke liye saari leaves, but normally employee ko sirf apni dikhti hain.
        // MVP ke liye hum sabhi leaves list kar rahe hain.
        $leaves = Leave::with(['user', 'leaveType'])->latest()->get();

        // Modal dropdown ke liye sirf active leave types bhejo
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('leaves.index', compact('leaves', 'leaveTypes'));
    }

    // 2. Employee: Apply for a New Leave
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            Leave::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending', // By default pending rahegi
            ]);

            DB::commit();
            return back()->with('success', 'Leave request submitted successfully. Waiting for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave Application Error: ' . $e->getMessage());
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
