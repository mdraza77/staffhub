@extends('layouts.main')

@section('title', 'Task Management | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Task Management</h1>
            <p class="text-sm text-gray-500 mt-1">Track your tasks and manage delegated work.</p>
        </div>

        <a href="{{ route('tasks.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Assign New Task
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
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
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                <th class="px-6 py-3 font-semibold">Task Details</th>
                                <th class="px-6 py-3 font-semibold">Assigned By</th>
                                <th class="px-6 py-3 font-semibold">Deadline</th>
                                <th class="px-6 py-3 font-semibold w-48">Progress</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($myTasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-800">{{ $task->title }}</p>
                                        @if ($task->media_links)
                                            <a href="{{ $task->media_links }}" target="_blank"
                                                class="text-xs text-blue-600 hover:underline mt-1 inline-block"><i
                                                    class="fa-solid fa-link"></i> View Attachments</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $task->assignedBy->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @php
                                            $deadline = \Carbon\Carbon::parse($task->deadline);
                                            $isOverdue = $deadline->isPast() && $task->status !== 'completed';
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-600 font-bold' : '' }}">
                                            {{ $deadline->format('d M, Y') }}
                                            @if ($isOverdue)
                                                <i class="fa-solid fa-circle-exclamation" title="Overdue"></i>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-indigo-600 h-2 rounded-full"
                                                    style="width: {{ $task->progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-gray-600">{{ $task->progress }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($task->status === 'completed')
                                            <span
                                                class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                            <span
                                                class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                Progress</span>
                                        @else
                                            <span
                                                class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                            class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                                            Update
                                        </button>
                                    </td>
                                </tr>

                                <div id="updateProgressModal{{ $task->id }}"
                                    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
                                    <div
                                        class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 overflow-hidden text-left">
                                        <div
                                            class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                                            <h3 class="text-lg font-bold text-gray-800">Update Task Progress</h3>
                                            <button type="button"
                                                onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                                class="text-gray-400 hover:text-gray-600"><i
                                                    class="fa-solid fa-xmark text-xl"></i></button>
                                        </div>
                                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="p-6 space-y-4">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-4">
                                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Instructions:
                                                    </p>
                                                    <div class="text-sm text-gray-700 prose prose-sm">
                                                        {!! $task->description !!}</div>
                                                    @if ($task->manager_remark)
                                                        <p class="text-xs text-red-600 mt-2 font-medium"><i
                                                                class="fa-solid fa-comment-dots"></i> Manager:
                                                            {{ $task->manager_remark }}</p>
                                                    @endif
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Progress
                                                        Slider ({{ $task->progress }}%)</label>
                                                    <input type="range" name="progress" min="0" max="100"
                                                        value="{{ $task->progress }}"
                                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                                                        oninput="this.nextElementSibling.innerText = this.value + '%'">
                                                    <div class="text-center text-sm font-bold text-indigo-600 mt-2">
                                                        {{ $task->progress }}%</div>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Your
                                                        Remarks</label>
                                                    <textarea name="employee_remark" rows="2"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                        placeholder="e.g., UI design completed, moving to API integration...">{{ $task->employee_remark }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Update
                                                        Media/Links</label>
                                                    <input type="text" name="media_links"
                                                        value="{{ $task->media_links }}"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                        placeholder="Link to your work (Drive, GitHub, etc.)">
                                                </div>
                                            </div>
                                            <div
                                                class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                                                <button type="button"
                                                    onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm">Save
                                                    Progress</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        <i class="fa-solid fa-mug-hot text-3xl text-gray-300 mb-2 block"></i>
                                        You have no assigned tasks right now.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        @if ($assignedByMe->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-teal-600"></i> Delegated Tasks (Assigned By Me)
                    <span
                        class="bg-teal-100 text-teal-700 text-xs px-2 py-0.5 rounded-full">{{ $assignedByMe->count() }}</span>
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                    <th class="px-6 py-3 font-semibold">Project Name</th>
                                    <th class="px-6 py-3 font-semibold">Task Title</th>
                                    <th class="px-6 py-3 font-semibold">Assigned To</th>
                                    <th class="px-6 py-3 font-semibold">Deadline</th>
                                    <th class="px-6 py-3 font-semibold w-48">Progress</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                    <th class="px-6 py-3 font-semibold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($assignedByMe as $task)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $task->project_name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $task->title }}</p>
                                            @if ($task->employee_remark)
                                                <p class="text-xs text-gray-500 mt-1"><i
                                                        class="fa-solid fa-reply text-gray-400"></i>
                                                    {{ Str::limit($task->employee_remark, 30) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                            {{ $task->assignedTo->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d M, Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-teal-500 h-2 rounded-full"
                                                        style="width: {{ $task->progress }}%"></div>
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-gray-600">{{ $task->progress }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($task->status === 'completed')
                                                <span
                                                    class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                            @elseif($task->status === 'in_progress')
                                                <span
                                                    class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In
                                                    Progress</span>
                                            @else
                                                <span
                                                    class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                                            <button onclick="toggleModal('editDetailsModal{{ $task->id }}')"
                                                class="text-blue-600 hover:text-blue-800 transition-colors"
                                                title="Edit Details">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" onclick="confirmDelete({{ $task->id }})"
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                title="Delete Task">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $task->id }}"
                                                action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>

                                    <div id="editDetailsModal{{ $task->id }}"
                                        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity">
                                        <div
                                            class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4 overflow-hidden text-left">
                                            <div
                                                class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                                                <h3 class="text-lg font-bold text-gray-800">Edit Task Details</h3>
                                                <button type="button"
                                                    onclick="toggleModal('editDetailsModal{{ $task->id }}')"
                                                    class="text-gray-400 hover:text-gray-600"><i
                                                        class="fa-solid fa-xmark text-xl"></i></button>
                                            </div>
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Task
                                                            Title <span class="text-red-500">*</span></label>
                                                        <input type="text" name="title" value="{{ $task->title }}"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Extend/Change
                                                            Deadline <span class="text-red-500">*</span></label>
                                                        <input type="date" name="deadline"
                                                            value="{{ $task->deadline }}"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Add
                                                            Note/Remark for Employee</label>
                                                        <textarea name="manager_remark" rows="2"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                                                            placeholder="e.g., Focus on UI first...">{{ $task->manager_remark }}</textarea>
                                                    </div>
                                                </div>
                                                <div
                                                    class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                                                    <button type="button"
                                                        onclick="toggleModal('editDetailsModal{{ $task->id }}')"
                                                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm">Update
                                                        Details</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
        }

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
@endpush
