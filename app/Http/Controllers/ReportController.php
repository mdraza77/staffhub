<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employees-Report',  only: ['employeesReport']),
        ];
    }

    public function employeesReport(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $roles       = Role::orderBy('name')->get();

        $query = User::with(['department', 'roles'])
            ->withTrashed()
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });

        // ===== FILTERS =====
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'deleted') {
                $query->whereNotNull('deleted_at');
            } else {
                $query->whereNull('deleted_at')
                    ->where('status', $request->status);
            }
        } else {
            // Default: deleted wale mat dikhao
            $query->whereNull('deleted_at');
        }

        if ($request->filled('joining_from')) {
            $query->whereDate('joining_date', '>=', $request->joining_from);
        }

        if ($request->filled('joining_to')) {
            $query->whereDate('joining_date', '<=', $request->joining_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $employees = $query->latest()->get();

        // Summary counts
        $summary = [
            'total'      => $employees->count(),
            'active'     => $employees->where('status', 'active')->whereNull('deleted_at')->count(),
            'inactive'   => $employees->where('status', 'inactive')->whereNull('deleted_at')->count(),
            'terminated' => $employees->where('status', 'terminated')->whereNull('deleted_at')->count(),
        ];

        return view('reports.employee', compact(
            'employees',
            'departments',
            'roles',
            'summary'
        ));
    }
}
