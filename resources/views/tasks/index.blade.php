@extends('layouts.main')

@section('title', 'Task Management | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Task Management</h1>
            <p class="text-sm text-gray-500 mt-1">Track your tasks and manage delegated work.</p>
        </div>

        @can('Task-Create')
            <x-create-button :url="route('tasks.create')" label="Assign New Task" />
        @endcan
    </div>

    <!-- Tab Navigation (Sub-Modules) -->
    <div class="mb-6 border-b border-gray-200 dark:border-zinc-800">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="taskTab" role="tablist">
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-all text-indigo-650 border-indigo-600 font-bold"
                    id="my-tasks-tab" data-tab-target="#my-tasks" type="button" role="tab" aria-controls="my-tasks"
                    aria-selected="true">
                    <i class="fa-solid fa-list-check mr-2"></i>My Tasks
                    <span
                        class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">{{ $myTasks->count() }}</span>
                </button>
            </li>
            @if ($tasksToTest->count() > 0)
                <li class="me-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg transition-all border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300"
                        id="tasks-to-test-tab" data-tab-target="#tasks-to-test" type="button" role="tab"
                        aria-controls="tasks-to-test" aria-selected="false">
                        <i class="fa-solid fa-flask mr-2"></i>Tasks to Test
                        <span
                            class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">{{ $tasksToTest->count() }}</span>
                    </button>
                </li>
            @endif
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-all border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300"
                    id="assigned-tasks-tab" data-tab-target="#assigned-tasks" type="button" role="tab"
                    aria-controls="assigned-tasks" aria-selected="false">
                    <i class="fa-solid fa-users-gear mr-2"></i>Delegated Tasks
                    <span
                        class="ml-1 px-2 py-0.5 text-xs font-bold rounded-full bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300">{{ $assignedByMe->count() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="space-y-6">

        {{-- ===== TAB CONTENT: MY Tasks ===== --}}
        <div id="my-tasks" class="tab-content" role="tabpanel" aria-labelledby="my-tasks-tab">
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="my_tasks" class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 dark:bg-zinc-950 text-gray-600 dark:text-zinc-400 text-sm border-b border-gray-200 dark:border-zinc-800">
                                <th class="px-6 py-3 font-semibold">Task Details</th>
                                <th class="px-6 py-3 font-semibold">Assigned By</th>
                                <th class="px-6 py-3 font-semibold">Tester</th>
                                <th class="px-6 py-3 font-semibold text-center">Priority</th>
                                <th class="px-6 py-3 font-semibold">Deadline</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-850">
                            @foreach ($myTasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-800 dark:text-zinc-100 cursor-help truncate max-w-[200px]"
                                            title="{{ $task->title }}">
                                            {{ Str::limit($task->title, 45) }}
                                        </p>
                                        @if ($task->project)
                                            <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1"><i
                                                    class="fa-solid fa-folder text-gray-400 dark:text-zinc-600"></i>
                                                Project:
                                                {{ $task->project->name }}{{ $task->project->trashed() ? ' [Deleted]' : '' }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                        {{ $task->assigner->name ?? 'N/A' }}
                                        ({{ $task->assigner->roles->first()->name ?? '' }})
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                        {{ $task->tester->name ?? 'None' }}
                                        ({{ $task->tester->roles->first()->name ?? '' }})
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($task->priority === 'critical')
                                            <span
                                                class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                    class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i>
                                                Critical</span>
                                        @elseif($task->priority === 'high')
                                            <span
                                                class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                                        @elseif($task->priority === 'low')
                                            <span
                                                class="bg-gray-150 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                                        @else
                                            <span
                                                class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                        @php
                                            $deadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline) : null;
                                            $isOverdue =
                                                $deadline &&
                                                $deadline->isPast() &&
                                                !in_array($task->status, ['completed', 'closed']);
                                        @endphp
                                        @if ($deadline)
                                            <span class="{{ $isOverdue ? 'text-red-650 dark:text-red-400 font-bold' : '' }}">
                                                {{ $deadline->format('d M, Y') }}
                                                @if ($isOverdue)
                                                    <i class="fa-solid fa-circle-exclamation" title="Overdue"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-zinc-650">No Deadline</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold">
                                        @if ($task->trashed())
                                            <span
                                                class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Deleted</span>
                                        @elseif ($task->status === 'completed')
                                            <span
                                                class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                Progress</span>
                                        @elseif($task->status === 'testing')
                                            <span
                                                class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                        @elseif($task->status === 'closed')
                                            <span
                                                class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                        @else
                                            <span
                                                class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{-- @if (!$task->trashed()) --}}
                                        @can('Task-ProgressUpdate')
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit Progress Details">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ===== TAB CONTENT: TASKS TO TEST ===== --}}
        @if ($tasksToTest->count() > 0)
            <div id="tasks-to-test" class="tab-content hidden" role="tabpanel" aria-labelledby="tasks-to-test-tab">
                <div
                    class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="tasks_to_test" class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-zinc-950 text-gray-600 dark:text-zinc-400 text-sm border-b border-gray-200 dark:border-zinc-800">
                                    <th class="px-6 py-3 font-semibold">Task Details</th>
                                    <th class="px-6 py-3 font-semibold">Assigned By</th>
                                    <th class="px-6 py-3 font-semibold">Assignee (Engineer)</th>
                                    <th class="px-6 py-3 font-semibold text-center">Priority</th>
                                    <th class="px-6 py-3 font-semibold">Deadline</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                    <th class="px-6 py-3 font-semibold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-zinc-850">
                                @foreach ($tasksToTest as $task)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800 dark:text-zinc-100 cursor-help truncate max-w-[200px]"
                                                title="{{ $task->title }}">
                                                {{ Str::limit($task->title, 45) }}
                                            </p>
                                            @if ($task->project)
                                                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1"><i
                                                        class="fa-solid fa-folder text-gray-400 dark:text-zinc-600"></i>
                                                    Project:
                                                    {{ $task->project->name }}{{ $task->project->trashed() ? ' [Deleted]' : '' }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                            {{ $task->assigner->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                            {{ $task->engineer->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($task->priority === 'critical')
                                                <span
                                                    class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                        class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i>
                                                    Critical</span>
                                            @elseif($task->priority === 'high')
                                                <span
                                                    class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                                            @elseif($task->priority === 'low')
                                                <span
                                                    class="bg-gray-150 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                                            @else
                                                <span
                                                    class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                            @php
                                                $deadline = $task->deadline
                                                    ? \Carbon\Carbon::parse($task->deadline)
                                                    : null;
                                                $isOverdue =
                                                    $deadline &&
                                                    $deadline->isPast() &&
                                                    !in_array($task->status, ['completed', 'closed']);
                                            @endphp
                                            @if ($deadline)
                                                <span class="{{ $isOverdue ? 'text-red-650 dark:text-red-400 font-bold' : '' }}">
                                                    {{ $deadline->format('d M, Y') }}
                                                    @if ($isOverdue)
                                                        <i class="fa-solid fa-circle-exclamation" title="Overdue"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-zinc-650">No Deadline</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold">
                                            @if ($task->trashed())
                                                <span
                                                    class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Deleted</span>
                                            @elseif ($task->status === 'completed')
                                                <span
                                                    class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                    Progress</span>
                                            @elseif($task->status === 'testing')
                                                <span
                                                    class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                            @elseif($task->status === 'closed')
                                                <span
                                                    class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                            @else
                                                <span
                                                    class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{-- @if (!$task->trashed()) --}}
                                            @can('Task-ProgressUpdate')
                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                    class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit Progress Details">
                                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                                </a>
                                            @endcan
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== TAB CONTENT: DELEGATED TASKS ===== --}}
        <div id="assigned-tasks" class="tab-content hidden" role="tabpanel" aria-labelledby="assigned-tasks-tab">
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="assigned_tasks" class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 dark:bg-zinc-950 text-gray-600 dark:text-zinc-400 text-sm border-b border-gray-200 dark:border-zinc-800">
                                <th class="px-6 py-3 font-semibold">Project Name</th>
                                <th class="px-6 py-3 font-semibold">Task Title</th>
                                <th class="px-6 py-3 font-semibold">Assigned To</th>
                                <th class="px-6 py-3 font-semibold">Tester</th>
                                <th class="px-6 py-3 font-semibold text-center">Priority</th>
                                <th class="px-6 py-3 font-semibold">Deadline</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-850">
                            @foreach ($assignedByMe as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-zinc-100">
                                        @if (auth()->user()->can('Task-View'))
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                                {{ $task->project->name ?? 'N/A' }}{{ $task->project && $task->project->trashed() ? ' [Deleted]' : '' }}
                                            </a>
                                        @else
                                            {{ $task->project->name ?? 'N/A' }}{{ $task->project && $task->project->trashed() ? ' [Deleted]' : '' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if (auth()->user()->can('Task-View'))
                                            <p class="text-sm font-bold text-gray-800 dark:text-zinc-100 cursor-help truncate max-w-[200px]"
                                                title="{{ $task->title }}">
                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                                    {{ Str::limit($task->title, 45) }}
                                                </a>
                                            </p>
                                        @else
                                            <p class="text-sm font-bold text-gray-800 dark:text-zinc-100 cursor-help truncate max-w-[200px]"
                                                title="{{ $task->title }}">
                                                {{ Str::limit($task->title, 45) }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-zinc-300">
                                        {{ $task->engineer->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                        {{ $task->tester->name ?? 'None' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($task->priority === 'critical')
                                            <span
                                                class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                    class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i>
                                                Critical</span>
                                        @elseif($task->priority === 'high')
                                            <span
                                                class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                                        @elseif($task->priority === 'low')
                                            <span
                                                class="bg-gray-150 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                                        @else
                                            <span
                                                class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-zinc-300">
                                        {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M, Y') : 'No Deadline' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold">
                                        {{-- @if ($task->trashed())
                                        <span
                                            class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Deleted</span>
                                        @elseif ($task->status === 'completed')
                                        <span
                                            class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                        <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                            Progress</span>
                                        @elseif($task->status === 'testing')
                                        <span
                                            class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                        @elseif($task->status === 'closed')
                                        <span
                                            class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                        @else
                                        <span
                                            class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                        @endif --}}
                                        <x-badge :value="$task->status" />
                                    </td>
                                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                                        @if ($task->trashed())
                                            @can('Task-Delete')
                                                <button type="button" onclick="confirmRestore({{ $task->id }})"
                                                    class="p-2 text-green-500 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Restore Task">
                                                    <i class="fa-solid fa-rotate-left text-base"></i>
                                                </button>
                                                <form id="restore-form-{{ $task->id }}" action="{{ route('tasks.restore', $task->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                            @endcan
                                        @else
                                            @can('Task-Edit')
                                                <a href="{{ route('tasks.edit', $task->id) }}"
                                                    class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit Details">
                                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                                </a>
                                            @endcan
                                            @can('Task-Delete')
                                                <button type="button" onclick="confirmDelete({{ $task->id }})"
                                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Delete Task">
                                                    <i class="fa-solid fa-trash-can text-base"></i>
                                                </button>
                                                <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This task will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function confirmRestore(id) {
            Swal.fire({
                title: "Restore Task?",
                text: "This task will be restored to active status.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#10b981",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
                }
            });
        }
    </script>
@endpush