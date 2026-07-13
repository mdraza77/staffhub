<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\EmployeeBreak;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Dashboard', only: ['index']),
        ];
    }

    public function index()
    {
        $stats = [
            'total_employees' => User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            })->count(),
            // 'active_employees'  => User::where('status', 'active')->count(),
            'active_employees' => User::where('status', 'active')
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'Super Admin');
                })
                ->count(),
            // 'inactive_employees' => User::where('status', 'inactive')->count(),
            'inactive_employees' => User::where('status', 'inactive')
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'Super Admin');
                })
                ->count(),
            'total_departments' => Department::count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'today_attendance' => Attendance::whereDate('date', today())->count(),
            'taking_break' => EmployeeBreak::where('status', 'ongoing')->count(),
        ];

        // $recentEmployees = User::with(['department', 'roles'])
        //     ->where('id', '!=', auth()->id())
        //     ->latest()
        //     ->take(5)
        //     ->get();

        $recentEmployees = User::with(['department', 'roles'])
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            })
            ->latest()
            ->take(5)
            ->get();

        $todayAttendance = Attendance::where('user_id', auth()->id())
            ->where('date', now()->format('Y-m-d'))
            ->first();

        $recentAnnouncements = Announcement::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $myTasks = auth()
            ->user()
            ->workingTasks()
            ->with('assigner')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recentEmployees',
            'todayAttendance',
            'recentAnnouncements',
            'myTasks',
        ));
    }
}
