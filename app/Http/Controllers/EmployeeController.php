<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employee-Index', only: ['index']),
            new Middleware('permission:Employee-Create', only: ['create', 'store']),
            new Middleware('permission:Employee-Edit', only: ['edit', 'update']),
            new Middleware('permission:Employee-Delete', only: ['destroy']),
            new Middleware('permission:Employee-Restore', only: ['Restore']),
            new Middleware('permission:Employee-ForceDelete', only: ['ForceDelete']),
            new Middleware('permission:Employee-View', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employees = User::withTrashed()
            ->with(['department', 'roles'])
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });

        if ($request->filled('status')) {
            $employees->where('status', $request->status);
        }

        $employees = $employees->latest()->get();

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        // Auto-generate employee ID
        $todayYear = Carbon::now()->format('y');  // e.g., 26
        $todayMonth = Carbon::now()->format('m');  // e.g., 07
        $prefix = "SH-{$todayYear}{$todayMonth}-";

        $lastEmployee = User::where('employee_id', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEmployee) {
            $lastSerial = (int) substr($lastEmployee->employee_id, -3);
            $nextSeq = $lastSerial + 1;
        } else {
            $nextSeq = 1;
        }

        $nextEmployeeId = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return view('employees.create', compact('departments', 'roles', 'nextEmployeeId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Auto-populate if not explicitly filled
        if (!$request->filled('employee_id')) {
            $todayYear = Carbon::now()->format('y');
            $todayMonth = Carbon::now()->format('m');
            $prefix = "SH-{$todayYear}{$todayMonth}-";

            $lastEmployee = User::where('employee_id', 'LIKE', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastEmployee) {
                $lastSerial = (int) substr($lastEmployee->employee_id, -3);
                $nextSeq = $lastSerial + 1;
            } else {
                $nextSeq = 1;
            }

            $generatedEmployeeId = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
            $request->merge(['employee_id' => $generatedEmployeeId]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id',
            'phone' => 'nullable|string|regex:/^\+?[1-9]\d{9,14}$/',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Max 2MB image
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Max 2MB signature
            'role' => 'nullable|exists:roles,name',
        ], [
            'phone.regex' => 'The phone number format must be valid (e.g. +918544568958 or 918544568958).',
        ]);

        try {
            DB::beginTransaction();  // Start Transaction

            $profilePath = null;
            $signaturePath = null;

            // Handle Profile Image Upload
            if ($request->hasFile('profile')) {
                // public/storage/profiles mein save hoga
                $profilePath = $request->file('profile')->store('profiles', 'public');
            }

            // Handle Signature Upload
            if ($request->hasFile('signature')) {
                // public/storage/signatures mein save hoga
                $signaturePath = $request->file('signature')->store('signatures', 'public');
            }

            // Create Employee/User
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'employee_id' => $request->employee_id,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
                'profile' => $profilePath,
                'signature' => $signaturePath,
            ]);

            if ($request->filled('role')) {
                $employee->assignRole($request->role);
            }

            DB::commit();  // Save changes to database

            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the actual error for debugging
            Log::error('Employee Creation Failed: ' . $e->getMessage());

            if (isset($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }

            if (isset($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }

            return back()->withInput()->with('error', 'Something went wrong while creating the employee. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        $employee->load('department');
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $departments = Department::all();
        $roles = Role::orderBy('name')->get();
        $employeeRole = $employee->roles->first()->name ?? null;
        return view('employees.edit', compact('employee', 'departments', 'roles', 'employeeRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'employee_id' => 'nullable|string|unique:users,employee_id,' . $employee->id,
            'phone' => 'nullable|string|regex:/^\+?[1-9]\d{9,14}$/',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'nullable|exists:roles,name',
        ], [
            'phone.regex' => 'The phone number format must be valid (e.g. +918544568958 or 918544568958).',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'employee_id' => $request->employee_id,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
            ];

            // Password sirf tab update hoga jab user ne naya diya ho
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Naya profile image upload hua toh purana delete karo
            if ($request->hasFile('profile')) {
                // Purani image delete karo agar exist karti hai
                if ($employee->profile) {
                    Storage::disk('public')->delete($employee->profile);
                }
                $data['profile'] = $request->file('profile')->store('profiles', 'public');
            }

            // Naya signature image upload hua toh purana delete karo
            if ($request->hasFile('signature')) {
                // Purani signature delete karo agar exist karti hai
                if ($employee->signature) {
                    Storage::disk('public')->delete($employee->signature);
                }
                $data['signature'] = $request->file('signature')->store('signatures', 'public');
            }

            $employee->update($data);

            if ($request->filled('role')) {
                $employee->syncRoles([$request->role]);
            } else {
                $employee->syncRoles([]);  // koi role nahi
            }

            DB::commit();

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Employee Update Failed: ' . $e->getMessage());

            // Nai image agar upload ho gayi thi par DB update fail hua toh usse bhi delete karo
            if (isset($data['profile'])) {
                Storage::disk('public')->delete($data['profile']);
            }

            return back()->withInput()->with('error', 'Something went wrong while updating the employee. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        $employee->delete();  // Soft Delete
        return redirect()->route('employees.index')->with('success', 'Employee soft deleted successfully.');
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('employees.index')->with('success', 'Employee restored successfully.');
    }

    public function forceDelete($id)
    {
        $employee = User::withTrashed()->findOrFail($id);

        // Profile image bhi delete karo permanently
        if ($employee->profile) {
            Storage::disk('public')->delete($employee->profile);
        }

        $employee->forceDelete();
        return redirect()->route('employees.index')->with('success', 'Employee permanently deleted.');
    }
}
