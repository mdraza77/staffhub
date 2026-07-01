<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Task-Index', only: ['index']),
            new Middleware('permission:Task-Create', only: ['create', 'store']),
            new Middleware('permission:Task-Edit', only: ['edit', 'update']),
            new Middleware('permission:Task-Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. My Tasks (Jo is user ko assign hue hain)
        $myTasks = Task::with('assignedBy')
            ->where('assigned_to', $userId)
            ->latest()
            ->get();

        // 2. Assigned By Me (Jo is user ne dusron ko assign kiye hain - For Managers)
        $assignedByMe = Task::with('assignedTo')
            ->where('assigned_by', $userId)
            ->latest()
            ->get();

        return view('tasks.index', compact('myTasks', 'assignedByMe'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('status', 'active')->get();

        return view('tasks.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'project_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'deadline' => 'required|date|after_or_equal:today',
            'description' => 'required|string',
            'media_links' => 'nullable|string',
            'manager_remark' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Task::create([
                'assigned_by' => Auth::id(),
                'assigned_to' => $request->assigned_to,
                'project_name' => $request->project_name,
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'progress' => 0,
                'status' => 'pending',
                'media_links' => $request->media_links,
                'manager_remark' => $request->manager_remark,
            ]);

            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Task assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task Assign Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while assigning the task.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $isAssignee = Auth::id() === $task->assigned_to;

        try {
            if ($isAssignee) {
                $request->validate([
                    'progress' => 'required|integer|min:0|max:100',
                    'employee_remark' => 'nullable|string',
                    'media_links' => 'nullable|string',
                ]);

                $status = 'in_progress';
                if ($request->progress == 0) $status = 'pending';
                if ($request->progress == 100) $status = 'completed';

                $task->update([
                    'progress' => $request->progress,
                    'status' => $status,
                    'employee_remark' => $request->employee_remark,
                    // Append new links if provided
                    'media_links' => $request->media_links ?? $task->media_links,
                ]);

                $message = 'Task progress updated successfully.';
            } else {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'deadline' => 'required|date',
                    'manager_remark' => 'nullable|string',
                ]);

                $task->update([
                    'title' => $request->title,
                    'deadline' => $request->deadline,
                    'manager_remark' => $request->manager_remark,
                ]);

                $message = 'Task details updated successfully.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Task Update Error: ' . $e->getMessage());
            return back()->with('error', 'Could not update the task.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Auth::id() !== $task->assigned_by && !Auth::user()->can('manage-all-tasks')) {
            return back()->with('error', 'You are not authorized to delete this task.');
        }

        try {
            $task->delete();
            return back()->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Task Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Could not delete the task.');
        }
    }
}
