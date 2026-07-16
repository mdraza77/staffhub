<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class LeaveTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:LeaveType-Index', only: ['index']),
            new Middleware('permission:LeaveType-Create', only: ['store']),
            new Middleware('permission:LeaveType-Edit', only: ['update']),
            new Middleware('permission:LeaveType-Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::withTrashed()->latest()->get();
        return view('leave_types.index', compact('leaveTypes'));
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
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'days_allowed' => 'required|integer|min:0',
        ]);

        try {
            LeaveType::create([
                'name' => $request->name,
                'days_allowed' => $request->days_allowed,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return back()->with('success', 'Leave type added successfully.');
        } catch (\Exception $e) {
            Log::error('Leave Type Create Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        // Unique validation mein current ID ko ignore karna zaroori hai
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'days_allowed' => 'required|integer|min:0',
        ]);

        try {
            $leaveType->update([
                'name' => $request->name,
                'days_allowed' => $request->days_allowed,
                // Agar checkbox check nahi hai, toh form value nahi bhejta, isliye fallback false lagaya hai
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return back()->with('success', 'Leave type updated successfully.');
        } catch (\Exception $e) {
            Log::error('Leave Type Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while updating.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        try {
            $leaveType->update([
                'is_active' => false,
            ]);

            $leaveType->delete();
            return back()->with('success', 'Leave type deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Leave Type Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Cannot delete this leave type. It might be in use.');
        }
    }

    public function restore($id)
    {
        $leaveType = LeaveType::withTrashed()->findOrFail($id);

        $leaveType->restore();

        $leaveType->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Leave type restored successfully.');
    }
}
