<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Project-Index', only: ['index']),
            new Middleware('permission:Project-Create', only: ['create', 'store']),
            new Middleware('permission:Project-Edit', only: ['edit', 'update']),
            new Middleware('permission:Project-Delete', only: ['destroy']),
            new Middleware('permission:Project-Restore', only: ['restore']),
            new Middleware('permission:Project-View', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::withTrashed()
            ->withCount(['tasks', 'defects'])
            ->latest()
            ->get();

        return view('project.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        ]);

        try {
            Project::create($request->only(['name', 'description', 'start_date', 'end_date', 'status']));

            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error('Project Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while creating project.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load([
            'tasks' => function ($query) {
                $query->with(['engineer', 'assigner'])->latest();
            },
            'defects' => function ($query) {
                $query->with(['assignee', 'reporter'])->latest();
            }
        ]);

        return view('project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        ]);

        try {
            $project->update($request->only(['name', 'description', 'start_date', 'end_date', 'status']));

            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Project Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while updating project.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project archived successfully.');
        } catch (\Exception $e) {
            Log::error('Project Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Could not archive the project.');
        }
    }

    /**
     * Restore the soft-deleted resource.
     */
    public function restore($id)
    {
        try {
            $project = Project::onlyTrashed()->findOrFail($id);
            $project->restore();
            return redirect()->route('projects.index')->with('success', 'Project restored successfully.');
        } catch (\Exception $e) {
            Log::error('Project Restore Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while restoring the project.');
        }
    }
}
