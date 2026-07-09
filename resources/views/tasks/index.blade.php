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

    @if (session('success'))
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
    @endif

    <div class="space-y-8">

        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i> My Tasks
                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full">{{ $myTasks->count() }}</span>
            </h2>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="my_tasks" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                <th class="px-6 py-3 font-semibold">Task Details</th>
                                <th class="px-6 py-3 font-semibold">Assigned By</th>
                                <th class="px-6 py-3 font-semibold">Tester</th>
                                <th class="px-6 py-3 font-semibold text-center">Priority</th>
                                <th class="px-6 py-3 font-semibold">Deadline</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($myTasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-805">
                                            {{ $task->title }}
                                        </p>
                                        @if ($task->project_name)
                                            <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-folder text-gray-400"></i>
                                                Project: {{ $task->project_name }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $task->assigner->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $task->tester->name ?? 'None' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($task->priority === 'critical')
                                            <span
                                                class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                    class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i> Critical</span>
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
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @php
                                            $deadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline) : null;
                                            $isOverdue = $deadline && $deadline->isPast() && !in_array($task->status, ['completed', 'closed']);
                                        @endphp
                                        @if ($deadline)
                                            <span class="{{ $isOverdue ? 'text-red-650 font-bold' : '' }}">
                                                {{ $deadline->format('d M, Y') }}
                                                @if ($isOverdue)
                                                    <i class="fa-solid fa-circle-exclamation" title="Overdue"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">No Deadline</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold">
                                        @if ($task->status === 'completed')
                                            <span
                                                class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                Progress</span>
                                        @elseif($task->status === 'ready_for_test')
                                            <span
                                                class="bg-purple-150 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">Ready
                                                for Test</span>
                                        @elseif($task->status === 'testing')
                                            <span
                                                class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                        @elseif($task->status === 'failed_testing')
                                            <span
                                                class="bg-red-150 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Failed in Testing</span>
                                        @elseif($task->status === 'closed')
                                            <span
                                                class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                        @else
                                            <span
                                                class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @can('Task-Edit')
                                            <a href="{{ route('tasks.show', $task->id) }}"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit Progress Details">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                                {{-- @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <i class="fa-solid fa-mug-hot text-3xl text-gray-300 mb-2 block"></i>
                                        You have no assigned tasks right now.
                                    </td>
                                </tr> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($tasksToTest->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-flask text-purple-600"></i> Tasks to Test (I am Tester)
                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">{{ $tasksToTest->count() }}</span>
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="tasks_to_test" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                    <th class="px-6 py-3 font-semibold">Task Details</th>
                                    <th class="px-6 py-3 font-semibold">Assigned By</th>
                                    <th class="px-6 py-3 font-semibold">Assignee (Engineer)</th>
                                    <th class="px-6 py-3 font-semibold text-center">Priority</th>
                                    <th class="px-6 py-3 font-semibold">Deadline</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                    <th class="px-6 py-3 font-semibold text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($tasksToTest as $task)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-805">
                                                {{ $task->title }}
                                            </p>
                                            @if ($task->project_name)
                                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-folder text-gray-400"></i>
                                                    Project: {{ $task->project_name }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $task->assigner->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $task->engineer->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($task->priority === 'critical')
                                                <span
                                                    class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                        class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i> Critical</span>
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
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @php
                                                $deadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline) : null;
                                                $isOverdue = $deadline && $deadline->isPast() && !in_array($task->status, ['completed', 'closed']);
                                            @endphp
                                            @if ($deadline)
                                                <span class="{{ $isOverdue ? 'text-red-650 font-bold' : '' }}">
                                                    {{ $deadline->format('d M, Y') }}
                                                    @if ($isOverdue)
                                                        <i class="fa-solid fa-circle-exclamation" title="Overdue"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400">No Deadline</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold">
                                            @if ($task->status === 'completed')
                                                <span
                                                    class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                    Progress</span>
                                            @elseif($task->status === 'ready_for_test')
                                                <span
                                                    class="bg-purple-150 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">Ready
                                                    for Test</span>
                                            @elseif($task->status === 'testing')
                                                <span
                                                    class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                            @elseif($task->status === 'failed_testing')
                                                <span
                                                    class="bg-red-150 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Failed in Testing</span>
                                            @elseif($task->status === 'closed')
                                                <span
                                                    class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                            @else
                                                <span
                                                    class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @can('Task-Edit')
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


        @if ($assignedByMe->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-teal-600"></i> Delegated Tasks (Assigned By Me)
                    <span class="bg-teal-100 text-teal-700 text-xs px-2 py-0.5 rounded-full">{{ $assignedByMe->count() }}</span>
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table id="assigned_tasks" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
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
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($assignedByMe as $task)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $task->project_name ?? 'N/A' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-850">
                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                    class="hover:underline hover:text-indigo-650">
                                                    {{ $task->title }}
                                                </a>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                            {{ $task->engineer->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $task->tester->name ?? 'None' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($task->priority === 'critical')
                                                <span
                                                    class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                                        class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i> Critical</span>
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
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M, Y') : 'No Deadline' }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold">
                                            @if ($task->status === 'completed')
                                                <span
                                                    class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                    Progress</span>
                                            @elseif($task->status === 'ready_for_test')
                                                <span
                                                    class="bg-purple-150 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">Ready
                                                    for Test</span>
                                            @elseif($task->status === 'testing')
                                                <span
                                                    class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                            @elseif($task->status === 'failed_testing')
                                                <span
                                                    class="bg-red-150 text-red-700 text-xs font-bold px-3 py-1 rounded-full">Failed in Testing</span>
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
                                            @can('Task-View')
                                                <a href="{{ route('tasks.show', $task->id) }}" title="View Details"
                                                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-eye text-base"></i>
                                                </a>
                                            @endcan
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
        @endif
    </div>
@endsection

@push('styles')
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
    </script>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#my_tasks').DataTable({
                    destroy: true,
                    dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
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
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#assigned_tasks').DataTable({
                    destroy: true,
                    dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
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
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#tasks_to_test').DataTable({
                    destroy: true,
                    dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
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
            });
        </script>
    @endpush
@endpush