<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees'  => User::count(),
            'active_employees' => User::where('status', 'active')->count(),
            'inactive_employees' => User::where('status', 'inactive')->count(),
            'total_departments' => Department::count(),
        ];

        // Recent 5 employees
        $recentEmployees = User::withTrashed()
            ->with(['department', 'roles'])
            ->where('id', '!=', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentEmployees'));
    }
}
