<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HolidayController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Holiday-Index', only: ['index']),
            new Middleware('permission:Holiday-Create', only: ['create', 'store']),
            new Middleware('permission:Holiday-Edit', only: ['edit', 'update']),
            new Middleware('permission:Holiday-Delete', only: ['destroy']),
            new Middleware('permission:Holiday-Restore', only: ['restore']),
            new Middleware('permission:Holiday-ForceDelete', only: ['forceDelete']),
            new Middleware('permission:Holiday-View', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::withTrashed()->latest()->get();

        return view('holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:public,optional,company',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $holiday = Holiday::withTrashed()->findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $holiday = Holiday::withTrashed()->findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $holiday = Holiday::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:public,optional,company',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Holiday soft deleted successfully.');
    }

    /**
     * Restore the soft-deleted resource.
     */
    public function restore($id)
    {
        $holiday = Holiday::onlyTrashed()->findOrFail($id);
        $holiday->restore();

        return redirect()->route('holidays.index')->with('success', 'Holiday restored successfully.');
    }

    /**
     * Permanently delete the resource.
     */
    public function forceDelete($id)
    {
        $holiday = Holiday::onlyTrashed()->findOrFail($id);
        $holiday->forceDelete();

        return redirect()->route('holidays.index')->with('success', 'Holiday permanently deleted.');
    }
}
