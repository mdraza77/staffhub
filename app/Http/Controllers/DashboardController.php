<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Dashboard',  only: ['index']),
        ];
    }
    public function index()
    {
        $stats = [
            'total_employees'   => User::count(),
            'active_employees'  => User::where('status', 'active')->count(),
            'inactive_employees' => User::where('status', 'inactive')->count(),
            'total_departments' => Department::count(),
            'pending_leaves'    => \App\Models\Leave::where('status', 'pending')->count(),
            'today_attendance'  => \App\Models\Attendance::whereDate('date', today())->count(),
        ];

        $recentEmployees = User::with(['department', 'roles'])
            ->where('id', '!=', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentEmployees'));
    }
}
