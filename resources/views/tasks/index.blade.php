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
                    <table class="w-full text-left border-collapse">
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
                            @forelse($myTasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-805">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="hover:underline hover:text-indigo-600">
                                                {{ $task->title }}
                                            </a>
                                        </p>
                                        @if ($task->project_name)
                                            <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-folder text-gray-400"></i> Project: {{ $task->project_name }}</p>
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
                                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i> Critical</span>
                                        @elseif($task->priority === 'high')
                                            <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                                        @elseif($task->priority === 'low')
                                            <span class="bg-gray-150 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                                        @else
                                            <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
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
                                            <span class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In Progress</span>
                                        @elseif($task->status === 'ready_for_test')
                                            <span class="bg-purple-150 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">Ready for Test</span>
                                        @elseif($task->status === 'testing')
                                            <span class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                        @elseif($task->status === 'closed')
                                            <span class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                        @else
                                            <span class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                            class="text-indigo-650 hover:text-indigo-805 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                                            Update
                                        </button>
                                    </td>
                                </tr>

                                <div id="updateProgressModal{{ $task->id }}"
                                    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center transition-opacity overflow-y-auto">
                                    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4 my-8 overflow-hidden text-left flex flex-col max-h-[85vh]">
                                        <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-100">
                                            <h3 class="text-lg font-bold text-gray-800">Update Task Findings & Comments</h3>
                                            <button type="button"
                                                onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                                class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                                        </div>

                                        <div class="p-6 space-y-6 overflow-y-auto flex-1">
                                            <!-- instructions -->
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Detailed Instructions:</p>
                                                <div class="text-sm text-gray-750 prose prose-sm max-w-none">
                                                    {!! $task->description !!}
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-gray-100">
                                                <!-- status update form section -->
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-800 mb-3"><i class="fa-solid fa-ellipsis-h text-indigo-500"></i> Change Status</h4>
                                                    <form action="{{ route('tasks.status.update', $task->id) }}" method="POST">
                                                        @csrf
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex-1">
                                                                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-sm" required>
                                                                    <option value="open" {{ $task->status === 'open' ? 'selected' : '' }}>Open / Pending</option>
                                                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                    <option value="ready_for_test" {{ $task->status === 'ready_for_test' ? 'selected' : '' }}>Ready for Test</option>
                                                                    <option value="testing" {{ $task->status === 'testing' ? 'selected' : '' }}>Testing</option>
                                                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                                    <option value="closed" {{ $task->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">Apply</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- document upload form -->
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-800 mb-3"><i class="fa-solid fa-file-arrow-up text-indigo-500"></i> Upload Document / Findings</h4>
                                                    <form action="{{ route('tasks.documents.store', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                                        @csrf
                                                        <div class="flex gap-2">
                                                            <input type="file" name="document" class="flex-1 text-sm text-gray-600 rounded-lg border border-gray-300 outline-none focus:ring-1 focus:ring-indigo-500" required>
                                                            <button type="submit" class="px-4 py-1.5 bg-indigo-650 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">Upload</button>
                                                        </div>
                                                        <input type="text" name="remark" placeholder="Remark for uploaded document (Optional)" class="w-full border border-gray-300 rounded-lg px-3 py-1 text-xs outline-none focus:ring-1 focus:ring-indigo-500">
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Files list -->
                                            <div class="pt-4 border-t border-gray-100">
                                                <h4 class="text-sm font-bold text-gray-800 mb-2"><i class="fa-solid fa-paperclip text-indigo-500"></i> Task Documents</h4>
                                                @if($task->documents->count() > 0)
                                                    <div class="space-y-2 max-h-[160px] overflow-y-auto">
                                                        @foreach($task->documents as $doc)
                                                            <div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-lg border border-gray-200 text-sm">
                                                                <div class="flex items-center gap-2">
                                                                    <i class="fa-solid fa-file-lines text-indigo-600"></i>
                                                                    <div>
                                                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-indigo-650 hover:underline font-medium text-xs">{{ $doc->file_name }}</a>
                                                                        @if($doc->remark)
                                                                            <span class="text-xs text-gray-500 ml-1">({{ $doc->remark }})</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <span class="text-[10px] text-gray-450">{{ $doc->user->name ?? 'User' }} &bull; {{ $doc->created_at->format('d M, h:i A') }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">No files uploaded yet.</p>
                                                @endif
                                            </div>

                                            <!-- Comments list and comment post form -->
                                            <div class="pt-4 border-t border-gray-100">
                                                <h4 class="text-sm font-bold text-gray-800 mb-2"><i class="fa-solid fa-comments text-indigo-500"></i> Task Comments</h4>
                                                
                                                <!-- current comments -->
                                                <div class="space-y-2 mb-3 max-h-[200px] overflow-y-auto">
                                                    @forelse($task->comments as $comment)
                                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                                            <div class="flex justify-between items-center mb-1 text-[11px] font-bold text-gray-550 border-b border-gray-150 pb-0.5">
                                                                <span>{{ $comment->user->name ?? 'User' }}</span>
                                                                <span>{{ $comment->created_at->format('d M, h:i A') }}</span>
                                                            </div>
                                                            <div class="text-xs text-gray-750">{{ $comment->comment }}</div>
                                                        </div>
                                                    @empty
                                                        <p class="text-xs text-gray-400 italic">No comments posted yet.</p>
                                                    @endforelse
                                                </div>

                                                <!-- post comment form -->
                                                <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST" class="flex gap-2">
                                                    @csrf
                                                    <input type="text" name="comment" placeholder="Write a comment..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-xs outline-none focus:ring-1 focus:ring-indigo-500" required>
                                                    <button type="submit" class="px-4 py-2 bg-indigo-650 text-white rounded-lg hover:bg-indigo-705 text-xs font-semibold">Post</button>
                                                </form>
                                            </div>

                                            <!-- status histories timeline -->
                                            <div class="pt-4 border-t border-gray-100">
                                                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status Timeline</h4>
                                                @if($task->statusHistories->count() > 0)
                                                    <div class="space-y-1 text-xs">
                                                        @foreach($task->statusHistories as $history)
                                                            <div class="flex items-center gap-1.5 text-gray-550">
                                                                <i class="fa-solid fa-circle-notch text-[8px] text-indigo-500"></i>
                                                                <span>Status updated from <strong class="text-gray-700">{{ $history->old_status }}</strong> to <strong class="text-gray-700">{{ $history->new_status }}</strong> by {{ $history->user->name ?? 'System' }}</span>
                                                                <span class="text-[10px] text-gray-400 ml-auto">{{ $history->created_at->format('d M, h:i A') }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-405 italic">No status changes logged.</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                                            <button type="button"
                                                onclick="toggleModal('updateProgressModal{{ $task->id }}')"
                                                class="px-4 py-2 text-xs text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Close</button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
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
                    <span class="bg-teal-100 text-teal-700 text-xs px-2 py-0.5 rounded-full">{{ $assignedByMe->count() }}</span>
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
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
                                                <a href="{{ route('tasks.show', $task->id) }}" class="hover:underline hover:text-indigo-650">
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
                                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i class="fa-solid fa-circle-exclamation text-xs mr-0.5"></i> Critical</span>
                                            @elseif($task->priority === 'high')
                                                <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                                            @elseif($task->priority === 'low')
                                                <span class="bg-gray-150 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                                            @else
                                                <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M, Y') : 'No Deadline' }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold">
                                            @if ($task->status === 'completed')
                                                <span class="bg-green-150 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="bg-blue-150 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">In Progress</span>
                                            @elseif($task->status === 'ready_for_test')
                                                <span class="bg-purple-150 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">Ready for Test</span>
                                            @elseif($task->status === 'testing')
                                                <span class="bg-amber-150 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">Testing</span>
                                            @elseif($task->status === 'closed')
                                                <span class="bg-gray-150 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Closed</span>
                                            @else
                                                <span class="bg-yellow-150 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">Open</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                                            @can('Task-Edit')
                                                <a href="{{ route('tasks.edit', $task->id) }}"
                                                    class="text-blue-600 hover:text-blue-805 transition-colors"
                                                    title="Edit Details">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            @endcan
                                            @can('Task-Delete')
                                                <button type="button" onclick="confirmDelete({{ $task->id }})"
                                                    class="text-red-650 hover:text-red-808 transition-colors"
                                                    title="Delete Task">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $task->id }}"
                                                    action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                    class="hidden">
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
