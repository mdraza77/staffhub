<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnnouncementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Announcement-Index', only: ['index']),
            new Middleware('permission:Announcement-Create', only: ['create', 'store']),
            new Middleware('permission:Announcement-Edit', only: ['edit', 'update']),
            new Middleware('permission:Announcement-Delete', only: ['destroy']),
            new Middleware('permission:Announcement-Restore', only: ['restore']),
            new Middleware('permission:Announcement-ForceDelete', only: ['forceDelete']),
            new Middleware('permission:Announcement-View', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::withTrashed()->with('creator')->latest()->get();
        return view('announcement.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('announcement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'publish_date' => 'nullable|date|before_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:published,draft',
        ]);

        $data['created_by'] = auth()->id();

        Announcement::create($data);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $announcement = Announcement::withTrashed()->with('creator')->findOrFail($id);
        return view('announcement.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        return view('announcement.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'publish_date' => 'nullable|date|before_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:published,draft',
        ]);

        $announcement->update($data);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement soft deleted successfully.');
    }

    /**
     * Restore the soft-deleted resource.
     */
    public function restore($id)
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->restore();

        return redirect()->route('announcements.index')->with('success', 'Announcement restored successfully.');
    }

    /**
     * Permanently delete the resource.
     */
    public function forceDelete($id)
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->forceDelete();

        return redirect()->route('announcements.index')->with('success', 'Announcement permanently deleted.');
    }
}
