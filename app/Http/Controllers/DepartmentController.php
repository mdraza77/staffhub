<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;

class DepartmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Department-Index', only: ['index']),
            new Middleware('permission:Department-Create', only: ['create', 'store']),
            new Middleware('permission:Department-Edit', only: ['edit', 'update']),
            new Middleware('permission:Department-Delete', only: ['destroy']),
            new Middleware('permission:Department-Restore', only: ['restore']),
            new Middleware('permission:Department-ForceDelete', only: ['forceDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withTrashed()->latest()->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            Department::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);

            Swal::toastSuccess([
                'title' => 'Department created successfully',
            ]);

            return redirect()->route('departments.index');
        } catch (\Exception $e) {
            Log::error('Department Creation Failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            Swal::toastError([
                'title' => 'Something went wrong',
            ]);

            return back()->withInput();
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
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $id],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $department->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);

            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch (\Exception $e) {
            Log::error('Department Update Failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'department_id' => $id,
            ]);

            return back()->withInput()->withErrors(['error' => 'Failed to update department']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }

    /**
     * Restore a soft deleted resource.
     */
    public function restore(string $id)
    {
        $department = Department::withTrashed()->findOrFail($id);

        if ($department->trashed()) {
            $department->restore();
            return redirect()->route('departments.index')->with('success', 'Department restored successfully!');
        }

        return redirect()->route('departments.index')->with('error', 'Department is not deleted.');
    }

    /**
     * Permanently delete a soft deleted resource.
     */
    public function forceDelete(string $id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->forceDelete();

        return redirect()->route('departments.index')->with('success', 'Department permanently deleted!');
    }
}
