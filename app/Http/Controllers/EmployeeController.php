<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::withTrashed()
            ->with('department')
            ->where('id', '!=', auth()->id())
            ->latest()
            ->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'nullable|string|unique:users,employee_id',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB image
        ]);

        try {
            DB::beginTransaction(); // Start Transaction

            $profilePath = null;

            // Handle Profile Image Upload
            if ($request->hasFile('profile')) {
                // public/storage/profiles mein save hoga
                $profilePath = $request->file('profile')->store('profiles', 'public');
            }

            // Create Employee/User
            User::create([
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
            ]);

            DB::commit(); // Save changes to database

            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Agar koi error aayi toh database mein kuch save nahi hoga

            // Log the actual error for debugging
            Log::error('Employee Creation Failed: ' . $e->getMessage());

            // Image agar upload ho gayi thi par DB mein save nahi hui, toh usko delete karo
            if (isset($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }

            return back()->withInput()->with('error', 'Something went wrong while creating the employee. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            // unique ignore karega current employee ka apna record
            'email'         => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'employee_id'   => 'nullable|string|unique:users,employee_id,' . $employee->id,
            'phone'         => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'designation'   => 'nullable|string|max:255',
            'joining_date'  => 'nullable|date',
            'status'        => 'required|in:active,inactive,terminated',
            'profile'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name'          => $request->name,
                'email'         => $request->email,
                'employee_id'   => $request->employee_id,
                'phone'         => $request->phone,
                'department_id' => $request->department_id,
                'designation'   => $request->designation,
                'joining_date'  => $request->joining_date,
                'status'        => $request->status,
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

            $employee->update($data);

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
    public function destroy(string $id)
    {
        //
    }
}
