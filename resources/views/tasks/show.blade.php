@extends('layouts.main')

@section('title', 'Task Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Task Details</h1>
            <p class="text-sm text-gray-500 mt-1">Review specifications, findings, and logs.</p>
        </div>
        <div class="flex items-center gap-3">
            @can('Task-Index')
                <a href="{{ route('tasks.index') }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">Back
                    to Tasks
                </a>
            @endcan
            @can('Task-Edit')
                <a href="{{ route('tasks.edit', $task->id) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Task
                </a>
            @endcan
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Side: Task core properties & details & comments (Col Span 2) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Core Meta Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div>
                    @if ($task->project_name)
                        <span
                            class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-2 inline-block">
                            <i class="fa-solid fa-folder text-indigo-400 mr-0.5"></i> {{ $task->project_name }}
                        </span>
                    @endif
                    <h2 class="text-xl font-extrabold text-gray-850">{{ $task->title }}</h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-b border-gray-100 py-5">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Assigned By</p>
                        <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->assigner->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Assigned To</p>
                        <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->engineer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Tester</p>
                        <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->tester->name ?? 'None' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Deadline & Status</p>
                        @php
                            $deadline = $task->deadline ? \Carbon\Carbon::parse($task->deadline) : null;
                            $isOverdue =
                                $deadline && $deadline->isPast() && !in_array($task->status, ['completed', 'closed']);
                        @endphp
                        <p class="text-sm font-bold mt-0.5 {{ $isOverdue ? 'text-red-650' : 'text-gray-700' }}">
                            {{ $deadline ? $deadline->format('d M, Y') : 'No Deadline' }}
                            @if ($isOverdue)
                                <span
                                    class="bg-red-50 text-red-750 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 uppercase">Overdue</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- instructions description -->
                <div>
                    <h3 class="text-sm font-bold text-gray-750 mb-2 uppercase tracking-wide">Detailed Instructions</h3>
                    <div
                        class="bg-gray-50 border border-gray-150 p-5 rounded-xl text-sm leading-relaxed text-gray-750 prose prose-sm max-w-none">
                        {!! $task->description !!}
                    </div>
                </div>
            </div>

            <!-- Comments Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-650"></i> Discussion & Comments
                    <span
                        class="bg-gray-100 text-gray-650 text-xs px-2 py-0.5 rounded-full">{{ $task->comments->count() }}</span>
                </h3>

                <!-- list of comments -->
                <div class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($task->comments as $comment)
                        <div class="flex gap-3">
                            <div
                                class="h-9 w-9 bg-indigo-50 border border-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 text-sm flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 bg-gray-50 border border-gray-100 p-3.5 rounded-xl text-sm">
                                <div class="flex justify-between items-center mb-1 text-xs">
                                    <span class="font-bold text-gray-800">{{ $comment->user->name ?? 'User' }}</span>
                                    <span class="text-gray-450">{{ $comment->created_at->diffForHumans() }} &bull;
                                        {{ $comment->created_at->format('d M, h:i A') }}</span>
                                </div>
                                <p class="text-gray-700 font-light break-words leading-relaxed">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-450 text-sm italic">
                            <i class="fa-solid fa-mug-hot text-2xl mb-1 text-gray-300 block"></i> No remarks or queries
                            posted. Start the discussion below.
                        </div>
                    @endforelse
                </div>

                <!-- comment post form -->
                @can('Task-Comment')
                    <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST"
                        class="flex items-start gap-3 border-t border-gray-50 pt-5">
                        @csrf
                        <div
                            class="h-9 w-9 bg-gray-105 border border-gray-200 rounded-full flex items-center justify-center font-bold text-gray-550 text-sm flex-shrink-0 uppercase">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="comment" rows="2" placeholder="Write a comment, remark, queries etc..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-sm"
                                required></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit"
                                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium text-xs shadow-sm transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-paper-plane"></i> Post Comment
                                </button>
                            </div>
                        </div>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Right Side: Status transitions & uploads & logs (Col Span 1) -->
        <div class="space-y-6">

            <!-- Quick Actions Card (Status changes & badges) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Properties & Actions</h3>

                <div class="space-y-3.5">
                    <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Current Status:</span>
                        @if ($task->status === 'completed')
                            <span
                                class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200">Completed</span>
                        @elseif($task->status === 'in_progress')
                            <span
                                class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">In
                                Progress</span>
                        @elseif($task->status === 'ready_for_test')
                            <span
                                class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full border border-purple-200">Ready
                                for Test</span>
                        @elseif($task->status === 'testing')
                            <span
                                class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-200">Testing</span>
                        @elseif($task->status === 'failed_testing')
                            <span
                                class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200">Failed in Testing</span>
                        @elseif($task->status === 'closed')
                            <span
                                class="bg-gray-100 text-gray-700 text-xs font-bold px-3 py-1 rounded-full border border-gray-200">Closed</span>
                        @else
                            <span
                                class="bg-yellow-105 text-yellow-750 text-xs font-bold px-3 py-1 rounded-full border border-yellow-150">Open</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Priority Level:</span>
                        @if ($task->priority === 'critical')
                            <span
                                class="bg-red-50 border border-red-150 text-red-750 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                    class="fa-solid fa-triangle-exclamation mr-0.5"></i> Critical</span>
                        @elseif($task->priority === 'high')
                            <span
                                class="bg-orange-50 border border-orange-150 text-orange-755 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                        @elseif($task->priority === 'low')
                            <span
                                class="bg-gray-50 border border-gray-200 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                        @else
                            <span
                                class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                        @endif
                    </div>
                </div>

                <!-- Update status form -->
                @can('Task-ProgressUpdate')
                    @php
                        $userId = Auth::id();
                        $isDeveloper = ($userId === $task->assigned_to);
                        $isTester = ($userId === $task->tester_id);
                        $isAssigner = ($userId === $task->assigned_by);
                        $isAdminOrManager = Auth::user()->can('Task-ManageAll') || Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);

                        $isDropdownDisabled = false;
                        $allowedStatuses = [];

                        if ($isAdminOrManager || $isAssigner) {
                            $allowedStatuses = ['open', 'in_progress', 'ready_for_test', 'testing', 'failed_testing', 'completed', 'closed'];
                        } elseif ($isDeveloper) {
                            $allowedStatuses = ['in_progress', 'ready_for_test'];
                            if (!in_array($task->status, $allowedStatuses)) {
                                $allowedStatuses[] = $task->status;
                            }
                        } elseif ($isTester) {
                            $testerAllowedCurrentStatuses = ['ready_for_test', 'testing', 'failed_testing', 'completed', 'closed'];
                            if (!in_array($task->status, $testerAllowedCurrentStatuses)) {
                                $isDropdownDisabled = true;
                            }
                            $allowedStatuses = ['testing', 'failed_testing', 'completed', 'closed'];
                            if (!in_array($task->status, $allowedStatuses)) {
                                $allowedStatuses[] = $task->status;
                            }
                        } else {
                            $isDropdownDisabled = true;
                        }
                    @endphp

                    <form action="{{ route('tasks.status.update', $task->id) }}" method="POST"
                        class="pt-3 border-t border-gray-100 space-y-2">
                        @csrf
                        <label class="block text-xs font-bold text-gray-700 uppercase">Change Task Status</label>
                        <div class="flex gap-2">
                            <select name="status"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-xs {{ $isDropdownDisabled ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                required {{ $isDropdownDisabled ? 'disabled' : '' }}>
                                @if(in_array('open', $allowedStatuses))
                                    <option value="open" {{ $task->status === 'open' ? 'selected' : '' }}>Open</option>
                                @endif
                                @if(in_array('in_progress', $allowedStatuses))
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                @endif
                                @if(in_array('ready_for_test', $allowedStatuses))
                                    <option value="ready_for_test" {{ $task->status === 'ready_for_test' ? 'selected' : '' }}>Ready for Test</option>
                                @endif
                                @if(in_array('testing', $allowedStatuses))
                                    <option value="testing" {{ $task->status === 'testing' ? 'selected' : '' }}>Testing</option>
                                @endif
                                @if(in_array('failed_testing', $allowedStatuses))
                                    <option value="failed_testing" {{ $task->status === 'failed_testing' ? 'selected' : '' }}>Failed in Testing</option>
                                @endif
                                @if(in_array('completed', $allowedStatuses))
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                @endif
                                @if(in_array('closed', $allowedStatuses))
                                    <option value="closed" {{ $task->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                @endif
                            </select>
                            <button type="submit"
                                class="bg-gray-800 hover:bg-gray-900 border border-gray-700 text-white px-3.5 py-2 rounded-lg text-xs font-bold transition-colors {{ $isDropdownDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $isDropdownDisabled ? 'disabled' : '' }}>Apply</button>
                        </div>
                    </form>
                @endcan
            </div>

            <!-- Documents / Deliverables Section -->
            @can('Task-Document')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center justify-between">
                        <span><i class="fa-solid fa-paperclip text-teal-600 mr-1"></i> Attachments / Docs</span>
                        <span
                            class="bg-teal-50 border border-teal-100 text-teal-700 text-xs px-2 py-0.5 rounded-full">{{ $task->documents->count() }}</span>
                    </h3>

                    <!-- Upload Attachment Form -->
                    <form action="{{ route('tasks.documents.store', $task->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-3 p-3 bg-gray-50 border border-gray-200 rounded-xl">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Choose File</label>
                            <input type="file" name="document"
                                class="w-full text-xs text-gray-700 border border-gray-300 rounded-lg bg-white px-2 py-1.5 focus:ring-1 focus:ring-indigo-500"
                                required>
                        </div>
                        <div>
                            <input type="text" name="remark" placeholder="Note / Brief remark (Optional)"
                                class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:ring-1 focus:ring-indigo-500 bg-white">
                        </div>
                        <button type="submit"
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white py-1.5 rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-upload"></i> Upload Attachment
                        </button>
                    </form>

                    <!-- list of uploaded documents -->
                    <div class="space-y-2.5 max-h-[220px] overflow-y-auto pr-1">
                        @forelse($task->documents as $doc)
                            <div
                                class="p-3 bg-white border border-gray-100 hover:border-gray-200 rounded-xl text-xs space-y-1.5 shadow-2xs hover:shadow-xs transition-shadow">
                                <div class="flex justify-between items-start gap-1">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                        class="text-indigo-650 hover:underline font-bold text-xs truncate flex-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-pdf text-[13px] text-red-500 flex-shrink-0"></i>
                                        {{ $doc->file_name }}
                                    </a>
                                </div>
                                @if ($doc->remark)
                                    <p
                                        class="text-gray-600 italic bg-gray-50 p-1.5 rounded border border-gray-100 select-none">
                                        {{ $doc->remark }}</p>
                                @endif
                                <div
                                    class="flex justify-between items-center text-[10px] text-gray-400 border-t border-gray-50 pt-1.5">
                                    <span>Uploaded by {{ $doc->user->name ?? 'User' }}</span>
                                    <span>{{ $doc->created_at->format('d M, h:i A') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-400 text-xs italic">
                                No files attached to this task.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endcan

            <!-- History log / Transitions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xs font-bold text-gray-805 uppercase tracking-wider mb-2">Status Timeline History</h3>

                @if ($task->statusHistories->count() > 0)
                    <div class="relative border-l-2 border-indigo-100 pl-4 ml-1 space-y-4">
                        @foreach ($task->statusHistories as $history)
                            <div class="relative text-xs">
                                <div
                                    class="absolute -left-[23px] top-1.5 h-2.5 w-2.5 rounded-full bg-indigo-505 border-2 border-white ring-4 ring-indigo-50">
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-gray-400">
                                    <span class="font-bold text-gray-650">{{ $history->user->name ?? 'System' }}</span>
                                    <span>{{ $history->created_at->format('d M, g:i a') }}</span>
                                </div>
                                <p class="text-gray-700 mt-0.5">
                                    Transitioned from

                                    <span class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[10px]">
                                        {{ ucwords(str_replace('_', ' ', $history->old_status)) }}
                                    </span>

                                    to

                                    <span
                                        class="bg-indigo-50 text-indigo-700 font-bold px-1 py-0.5 rounded font-mono text-[10px]">
                                        {{ ucwords(str_replace('_', ' ', $history->new_status)) }}
                                    </span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">No transition changes logged.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
