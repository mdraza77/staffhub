<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskDocument;
use App\Models\TaskStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Task-Index', only: ['index']),
            new Middleware('permission:Task-Create', only: ['create', 'store']),
            new Middleware('permission:Task-Edit', only: ['edit', 'update']),
            new Middleware('permission:Task-Delete', only: ['destroy']),
            new Middleware('permission:Task-Comment', only: ['storeComment']),
            new Middleware('permission:Task-Document', only: ['storeDocument']),
            new Middleware('permission:Task-ProgressUpdate', only: ['updateStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. My Tasks (assigned to user)
        $myTasks = Task::with(['assigner', 'engineer', 'tester'])
            ->where('assigned_to', $userId)
            ->latest()
            ->get();

        // 2. Assigned By Me (assigned by user)
        $assignedByMe = Task::with(['assigner', 'engineer', 'tester'])
            ->where('assigned_by', $userId)
            ->latest()
            ->get();

        // 3. Tasks to Test (where user is the tester)
        $tasksToTest = Task::with(['assigner', 'engineer', 'tester'])
            ->where('tester_id', $userId)
            ->latest()
            ->get();

        return view('tasks.index', compact('myTasks', 'assignedByMe', 'tasksToTest'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('status', 'active')->where('id', '!=', Auth::id())->get();

        return view('tasks.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'tester_id' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,critical',
            'status' => 'nullable|in:open,in_progress,testing,completed,closed',
        ]);

        try {
            DB::beginTransaction();

            $task = Task::create([
                'assigned_by' => Auth::id(),
                'assigned_to' => $request->assigned_to,
                'tester_id' => $request->tester_id,
                'project_name' => $request->project_name,
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'priority' => $request->priority ?? 'medium',
                'status' => $request->status ?? 'open',
            ]);

            // Log the initial status in task_status_histories
            $task->statusHistories()->create([
                'old_status' => 'none',
                'new_status' => $task->status,
                'changed_by' => Auth::id(),
                'remark' => 'Task created',
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
        $task->load([
            'assigner',
            'engineer',
            'tester',
            'comments.user',
            'documents.user',
            'statusHistories.user'
        ]);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $employees = User::where('status', 'active')->where('id', '!=', Auth::id())->get();
        return view('tasks.edit', compact('task', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'project_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'tester_id' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,in_progress,testing,completed,closed',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $task->status;
            $newStatus = $request->status;

            $task->update([
                'project_name' => $request->project_name,
                'title' => $request->title,
                'assigned_to' => $request->assigned_to,
                'tester_id' => $request->tester_id,
                'deadline' => $request->deadline,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $newStatus,
            ]);

            if ($oldStatus !== $newStatus) {
                $task->statusHistories()->create([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Task details updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while updating the task.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Auth::id() !== $task->assigned_by && !Auth::user()->can('Task-ManageAll')) {
            return back()->with('error', 'You are not authorized to delete this task.');
        }

        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Task Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Could not delete the task.');
        }
    }

    /**
     * Store a comment on the task.
     */
    public function storeComment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        if (Auth::id() !== $task->assigned_to && Auth::id() !== $task->assigned_by && Auth::id() !== $task->tester_id && !Auth::user()->can('Task-ManageAll')) {
            return back()->with('error', 'You are not authorized to comment on this task.');
        }

        $userId = Auth::id();
        $isAdminOrManager = Auth::user()->can('Task-ManageAll') || Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);
        $isAssigner = ($userId === $task->assigned_by);

        if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner) {
            return back()->with('error', 'This task is closed. Comments are locked.');
        }

        try {
            $task->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            Log::error('Task Comment Store Error: ' . $e->getMessage());
            return back()->with('error', 'Could not add comment.');
        }
    }

    /**
     * Upload and store a document on the task.
     */
    public function storeDocument(Request $request, Task $task)
    {
        $request->validate([
            'document' => 'required|file|max:10240',  // Max 10MB file
            'remark' => 'nullable|string|max:255',
        ]);

        $filePath = null;

        $userId = Auth::id();
        $isAdminOrManager = Auth::user()->can('Task-ManageAll') || Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);
        $isAssigner = ($userId === $task->assigned_by);

        if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner) {
            return back()->with('error', 'This task is closed. Document uploads are locked.');
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('task_documents', 'public');

                $task->documents()->create([
                    'user_id' => Auth::id(),
                    'file_name' => $originalName,
                    'file_path' => $filePath,
                    'remark' => $request->remark,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task Document Store Error: ' . $e->getMessage());

            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->with('error', 'Could not upload document.');
        }
    }

    /**
     * Direct toggle or update of task status.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,testing,completed,closed',
            'remark' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $isDeveloper = ($userId === $task->assigned_to);
        $isTester = ($userId === $task->tester_id);
        $isAssigner = ($userId === $task->assigned_by);
        $isAdminOrManager = Auth::user()->can('Task-ManageAll') || Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);

        $newStatus = $request->status;
        $oldStatus = $task->status;

        // Enforce role-based restrictions
        if ($oldStatus === 'closed' && !$isAdminOrManager && !$isAssigner) {
            return back()->with('error', 'This task is closed. Only managers and admins can reopen it.');
        }

        if (!$isAdminOrManager && !$isAssigner) {
            if ($isDeveloper) {
                // Developer can only set to in_progress or testing
                if (!in_array($newStatus, ['in_progress', 'testing'])) {
                    return back()->with('error', 'Developers can only set task status to In Progress or Testing.');
                }
            } elseif ($isTester) {
                // Tester cannot update status unless task status is testing
                if ($oldStatus !== 'testing') {
                    return back()->with('error', 'You cannot change the status until the developer puts it in testing.');
                }
                // Tester can only set task status to Completed or In Progress (if failed testing)
                if (!in_array($newStatus, ['completed', 'in_progress'])) {
                    return back()->with('error', 'Testers can only set task status to Completed or In Progress.');
                }
            } else {
                return back()->with('error', 'You are not authorized to update the status of this task.');
            }
        }

        if ($oldStatus === $newStatus) {
            return back()->with('info', 'Status is already ' . $newStatus);
        }

        try {
            DB::beginTransaction();

            $task->update(['status' => $newStatus]);

            $task->statusHistories()->create([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $userId,
                'remark' => $request->remark,
            ]);

            DB::commit();
            return back()->with('success', 'Task status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Task Status Update Error: ' . $e->getMessage());
            return back()->with('error', 'Could not update task status.');
        }
    }
}
