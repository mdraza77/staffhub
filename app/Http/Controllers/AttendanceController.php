<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttendanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Attendance-Index',  only: ['index']),
            new Middleware('permission:Attendance-Marking', only: ['punch']),
            new Middleware('permission:Attendance-View',   only: ['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->first();
        $attendances = Attendance::with(['user.department'])
            ->where('date', $date)
            ->latest()
            ->get();

        return view('attendance.index', compact('attendances', 'date', 'todayAttendance'));
    }

    public function punch(Request $request)
    {
        $request->validate([
            'note' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction(); // Start Transaction

            $user = Auth::user();
            $today = Carbon::today()->format('Y-m-d');
            $currentTime = Carbon::now()->format('H:i:s'); // Current Time (e.g., 09:30:00)

            // Check if there's already an attendance record for today for this user
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->lockForUpdate() // Prevent race conditions
                ->first();

            if (!$attendance) {
                // Scenario 1: Today's attendance record doesn't exist -> CHECK-IN
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'check_in_time' => $currentTime,
                    'status' => 'present',
                    'note' => $request->note,
                ]);

                $message = 'Successfully checked in at ' . Carbon::now()->format('h:i A');
            } else {
                // Scenario 2: Entry found for today, check if check-out time is already filled
                if ($attendance->check_out_time !== null) {
                    // If check-out time is already filled, it means the user has already checked out for today
                    DB::rollBack();
                    return back()->with('error', 'You have already checked out for today.');
                }

                // Scenario 3: Entry found for today, check-out time is null -> CHECK-OUT
                $attendance->update([
                    'check_out_time' => $currentTime
                ]);

                $message = 'Successfully checked out at ' . Carbon::now()->format('h:i A');
            }

            DB::commit(); // Save changes to database
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack(); // If any error occurs, rollback the transaction to maintain data integrity

            // Log file for debugging purposes
            Log::error('Attendance Punch Failed for User ' . Auth::id() . ' : ' . $e->getMessage());

            return back()->with('error', 'Something went wrong while marking attendance. Please try again or contact IT.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
