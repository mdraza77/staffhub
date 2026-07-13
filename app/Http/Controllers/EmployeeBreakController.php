<?php

namespace App\Http\Controllers;

use App\Models\BreakType;
use App\Models\EmployeeBreak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeBreakController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Break-Room-Access', only: ['index', 'startBreak', 'endBreak']),
            new Middleware('permission:Break-History-View', only: ['history']),
        ];
    }

    /**
     * Display the Break Room.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Get logged-in user's active break
        $activeBreak = EmployeeBreak::with('breakType')
            ->where('user_id', $userId)
            ->where('status', 'ongoing')
            ->first();

        // 2. Get active breaks of other employees
        $otherActiveBreaks = EmployeeBreak::with(['user.department', 'breakType'])
            ->where('status', 'ongoing')
            ->where('user_id', '!=', $userId)
            ->latest('started_at')
            ->get();

        // 3. Get active break types
        $breakTypes = BreakType::where('is_active', true)->orderBy('name')->get();

        // 4. Get today's completed breaks of all employees (for recent activity)
        $recentCompletedBreaks = EmployeeBreak::with(['user', 'breakType'])
            ->where('status', 'completed')
            ->whereDate('started_at', Carbon::today())
            ->latest('ended_at')
            ->take(10)
            ->get();

        return view('break.breaks.room', compact('activeBreak', 'otherActiveBreaks', 'breakTypes', 'recentCompletedBreaks'));
    }

    /**
     * Start a break for the logged-in user.
     */
    public function startBreak(Request $request)
    {
        $request->validate([
            'break_type_id' => 'required|exists:break_types,id',
            'remark' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        // Check if already on a break
        $existing = EmployeeBreak::where('user_id', $userId)
            ->where('status', 'ongoing')
            ->exists();

        if ($existing) {
            return back()->with('error', 'You are already on an active break. Please end it first!');
        }

        try {
            DB::beginTransaction();

            $breakType = BreakType::findOrFail($request->break_type_id);
            $startedAt = Carbon::now();
            $expectedEndTime = $startedAt->copy()->addMinutes($breakType->duration_minutes);

            EmployeeBreak::create([
                'user_id' => $userId,
                'break_type_id' => $breakType->id,
                'started_at' => $startedAt,
                'expected_end_time' => $expectedEndTime,
                'status' => 'ongoing',
                'remark' => $request->remark,
            ]);

            DB::commit();
            return redirect()->route('break-room.index')->with('success', 'Enjoy your break!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Start Break Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to start your break.');
        }
    }

    /**
     * End the user's active break.
     */
    public function endBreak()
    {
        $userId = Auth::id();

        $activeBreak = EmployeeBreak::where('user_id', $userId)
            ->where('status', 'ongoing')
            ->first();

        if (!$activeBreak) {
            return back()->with('error', 'No active break found.');
        }

        try {
            $activeBreak->update([
                'ended_at' => Carbon::now(),
                'status' => 'completed',
            ]);

            return redirect()->route('break-room.index')->with('success', 'Welcome back to work!');
        } catch (\Exception $e) {
            Log::error('End Break Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to end your break.');
        }
    }

    /**
     * Display a history list of all breaks.
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $isAdminOrHR = $user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('HR Manager');

        $query = EmployeeBreak::with(['user.department', 'breakType'])->latest('started_at');

        if ($isAdminOrHR) {
            // Filters for Admin/HR
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('break_type_id')) {
                $query->where('break_type_id', $request->break_type_id);
            }
            if ($request->filled('date')) {
                $query->whereDate('started_at', $request->date);
            }

            $breaks = $query->paginate(20);
            $employees = User::orderBy('name')->get();
            $breakTypes = BreakType::orderBy('name')->get();
        } else {
            // Regular employees only see their own history
            $breaks = $query->where('user_id', $user->id)->paginate(20);
            $employees = null;
            $breakTypes = null;
        }

        return view('break.breaks.history', compact('breaks', 'employees', 'breakTypes', 'isAdminOrHR'));
    }
}
