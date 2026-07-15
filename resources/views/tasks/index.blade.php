@extends('layouts.main')

@section('title', 'Task Management | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Task Management</h1>
            <p class="text-sm text-gray-500 mt-1">Track your tasks and manage delegated work.</p>
        </div>

        @can('Task-Create')
            <a href="{{ route('tasks.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Assign New Task
            </a>
        @endcan
    </div>

    {{-- @if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif
    @if (session('info'))
    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fa-solid fa-info-circle"></i> {{ session('info') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif --}}

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
                                        @if ($task->project_name)
                                            <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1"><i
                                                    class="fa-solid fa-folder text-gray-400 dark:text-zinc-600"></i>
                                                Project: {{ $task->project_name }}</p>
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
                                        @if ($task->status === 'completed')
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
                                        @can('Task-ProgressUpdate')
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit Progress Details">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan
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
                                            @if ($task->project_name)
                                                <p class="text-xs text-gray-500 dark:text-zinc-500 mt-1"><i
                                                        class="fa-solid fa-folder text-gray-400 dark:text-zinc-600"></i>
                                                    Project: {{ $task->project_name }}</p>
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
                                            @if ($task->status === 'completed')
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
                                            @can('Task-ProgressUpdate')
                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                    class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit Progress Details">
                                                    <i class="fa-solid fa-pen-to-square text-base"></i>
                                                </a>
                                            @endcan
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
                                                {{ $task->project_name ?? 'N/A' }}
                                            </a>
                                        @else
                                            {{ $task->project_name ?? 'N/A' }}
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
                                        @if ($task->status === 'completed')
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
                                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                                        {{-- View --}}
                                        {{-- @can('Task-View')
                                        <a href="{{ route('tasks.show', $task->id) }}" title="View Details"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="fa-solid fa-eye text-base"></i>
                                        </a>
                                        @endcan --}}
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
                                                <i class="fa-solid fa-trash text-base"></i>
                                            </button>
                                            <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
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

        $(document).ready(function () {
            // Tab switching logic
            $('#taskTab button').on('click', function () {
                // Clear active styles
                $('#taskTab button')
                    .removeClass(
                        'border-indigo-600 text-indigo-650 dark:border-purple-500 dark:text-purple-400 font-bold'
                    )
                    .addClass(
                        'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300'
                    );

                // Set active style on current
                $(this)
                    .removeClass(
                        'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-zinc-400 dark:hover:text-zinc-300'
                    )
                    .addClass(
                        'border-indigo-600 text-indigo-650 dark:border-purple-500 dark:text-purple-400 font-bold'
                    );

                // Toggle panels
                $('.tab-content').addClass('hidden');
                $($(this).data('tab-target')).removeClass('hidden');

                // Save active tab ID to localStorage
                localStorage.setItem('activeTaskTab', $(this).attr('id'));

                // Adjust column calculation for active Datatables
                setTimeout(function () {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust();
                }, 50);
            });

            // Initialize Datatables
            $('#my_tasks').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
                buttons: [{
                    extend: 'copy',
                    className: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 mr-2 transition-colors'
                },
                {
                    extend: 'excel',
                    className: 'bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors'
                },
                {
                    extend: 'pdf',
                    className: 'bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors'
                },
                {
                    extend: 'print',
                    className: 'bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 transition-colors'
                }
                ],
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search here...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });

            $('#assigned_tasks').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
                buttons: [{
                    extend: 'copy',
                    className: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 mr-2 transition-colors'
                },
                {
                    extend: 'excel',
                    className: 'bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors'
                },
                {
                    extend: 'pdf',
                    className: 'bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors'
                },
                {
                    extend: 'print',
                    className: 'bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 transition-colors'
                }
                ],
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search here...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });

            if ($('#tasks_to_test').length) {
                $('#tasks_to_test').DataTable({
                    destroy: true,
                    dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100 dark:border-zinc-800"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 dark:text-zinc-455"i><"flex items-center"p>>',
                    buttons: [{
                        extend: 'copy',
                        className: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 mr-2 transition-colors'
                    },
                    {
                        extend: 'excel',
                        className: 'bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'pdf',
                        className: 'bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'print',
                        className: 'bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 transition-colors'
                    }
                    ],
                    pageLength: 10,
                    language: {
                        search: "",
                        searchPlaceholder: "Search here...",
                        lengthMenu: "_MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries"
                    }
                });
            }

            // Restore active tab from localStorage if exists
            var activeTab = localStorage.getItem('activeTaskTab');
            if (activeTab && $('#' + activeTab).length) {
                $('#' + activeTab).trigger('click');
            } else {
                $('#my-tasks-tab').trigger('click');
            }
        });
    </script>
@endpush