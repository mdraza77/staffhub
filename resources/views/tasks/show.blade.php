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

    {{-- @if (session('success'))
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
    @endif --}}

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
                        <a href="{{ route('employees.show', $task->assigned_by) }}">
                            <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->assigner->name ?? 'N/A' }}
                                ({{ $task->assigner->roles->first()->name ?? '' }})</p>
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Assigned To</p>
                        <a href="{{ route('employees.show', $task->assigned_to) }}">
                            <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->engineer->name ?? 'N/A' }}
                                ({{ $task->engineer->roles->first()->name ?? '' }})</p>
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Tester</p>
                        <a href="{{ route('employees.show', $task->tester_id) }}">
                            <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $task->tester->name ?? 'None' }}
                                ({{ $task->tester->roles->first()->name ?? '' }})</p>
                        </a>
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
                                    class="bg-red-50 text-red-700 border border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50 text-[10px] font-bold px-1.5 py-0.5 rounded ml-1 uppercase">Overdue</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- instructions description -->
                <div>
                    <h3 class="text-sm font-bold text-gray-750 mb-2 uppercase tracking-wide">Detailed Instructions</h3>
                    <div
                        class="bg-gray-50 border border-gray-150 p-5 rounded-xl text-sm leading-relaxed text-gray-755 prose prose-sm max-w-none">
                        {!! $task->description !!}
                    </div>
                </div>
            </div>

            <!-- Comments Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-650"></i> Discussion & Comments
                    <span
                        class="bg-gray-100 text-gray-655 text-xs px-2 py-0.5 rounded-full">{{ $task->comments->count() }}</span>
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
                    @php
                        $userId = Auth::id();
                        $isAdminOrManager =
                            Auth::user()->can('Task-ManageAll') ||
                            Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);
                        $isAssigner = $userId === $task->assigned_by;
                    @endphp

                    @if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner)
                        <div
                            class="mt-4 bg-gray-50 border border-gray-250 text-gray-500 text-xs px-4 py-3.5 rounded-xl flex items-center gap-2 justify-center font-medium italic">
                            <i class="fa-solid fa-lock text-gray-400"></i> Discussion is closed because this task is closed.
                        </div>
                    @else
                        <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST"
                            class="flex items-start gap-3 border-t border-gray-50 pt-5">
                            @csrf
                            <div
                                class="h-9 w-9 bg-gray-100 border border-gray-200 rounded-full flex items-center justify-center font-bold text-gray-550 text-sm flex-shrink-0 uppercase">
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
                    @endif
                @endcan
            </div>

            <!-- ===== STATUS TRANSITION TIMELINE HISTORY (JIRA/GITHUB STYLE) ===== -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-indigo-600"></i> Status Timeline History
                    <span
                        class="bg-gray-100 text-gray-655 text-xs px-2 py-0.5 rounded-full">{{ $task->statusHistories->count() }}</span>
                </h3>

                @if ($task->statusHistories->count() > 0)
                    <div class="relative border-l-2 border-indigo-100 pl-6 ml-4 space-y-6 py-2">
                        @foreach ($task->statusHistories as $history)
                            <div class="relative text-xs">
                                {{-- Timeline Icon/Dot --}}
                                <div
                                    class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-white border-2 border-indigo-500 flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-right-arrow-left text-[8px] text-indigo-500"></i>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between gap-1 text-[11px] text-gray-400">
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="font-bold text-gray-700 text-sm">{{ $history->user->name ?? 'System' }}</span>
                                        @if ($history->user && $history->user->roles->isNotEmpty())
                                            <span
                                                class="px-1.5 py-0.2 bg-gray-100 text-gray-500 rounded text-[9px] font-medium border border-gray-200">
                                                {{ $history->user->roles->first()->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <span>{{ $history->created_at->format('d M Y, g:i A') }}
                                        ({{ $history->created_at->diffForHumans() }})
                                    </span>
                                </div>

                                <p class="text-gray-700 mt-2 text-xs flex items-center gap-1.5 flex-wrap">
                                    Transitioned status from
                                    <span
                                        class="bg-gray-100 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-full font-semibold uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $history->old_status) }}
                                    </span>
                                    to
                                    <span
                                        class="bg-indigo-50 text-indigo-750 border border-indigo-100 px-2 py-0.5 rounded-full font-bold uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $history->new_status) }}
                                    </span>
                                </p>

                                @if ($history->remark)
                                    <div
                                        class="mt-3 p-3.5 bg-gray-50 border border-gray-150 rounded-xl text-gray-700 leading-relaxed relative max-w-3xl shadow-3xs">
                                        <div
                                            class="flex items-center gap-1 mb-1.5 text-[9px] font-bold uppercase text-gray-400 tracking-wider">
                                            <i class="fa-solid fa-comment-dots text-indigo-400 text-xs"></i>
                                            Change Remark / Explanation
                                        </div>
                                        <p class="font-light text-xs whitespace-pre-line">{{ $history->remark }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">No transition changes logged for this task.</p>
                @endif
            </div>

        </div>

        <!-- Right Side: Status transitions & uploads & logs (Col Span 1) -->
        <div class="space-y-6">

            <!-- Quick Actions Card (Status changes & badges) -->
            <div
                class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-800 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 dark:text-zinc-100 uppercase tracking-wider mb-2">Properties &
                    Actions</h3>

                <div class="space-y-3.5">
                    <div
                        class="flex justify-between items-center text-sm border-b border-gray-100 dark:border-zinc-800 pb-2.5">
                        <span class="text-gray-500 dark:text-zinc-400">Current Status:</span>
                        @if ($task->status === 'completed')
                            <span
                                class="bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/40 dark:text-green-400 dark:border-green-900/50 text-xs font-bold px-3 py-1 rounded-full uppercase">Completed</span>
                        @elseif($task->status === 'in_progress')
                            <span
                                class="bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/50 text-xs font-bold px-3 py-1 rounded-full uppercase">In
                                Progress</span>
                        @elseif($task->status === 'testing')
                            <span
                                class="bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/50 text-xs font-bold px-3 py-1 rounded-full uppercase">Testing</span>
                        @elseif($task->status === 'closed')
                            <span
                                class="bg-gray-50 text-gray-700 border border-gray-200 dark:bg-zinc-800/40 dark:text-zinc-400 dark:border-zinc-700/50 text-xs font-bold px-3 py-1 rounded-full uppercase">Closed</span>
                        @else
                            <span
                                class="bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-950/40 dark:text-yellow-400 dark:border-yellow-900/50 text-xs font-bold px-3 py-1 rounded-full uppercase">Open</span>
                        @endif
                    </div>

                    <div
                        class="flex justify-between items-center text-sm border-b border-gray-100 dark:border-zinc-800 pb-2.5">
                        <span class="text-gray-500 dark:text-zinc-400">Priority Level:</span>
                        @if ($task->priority === 'critical')
                            <span
                                class="bg-red-50 border border-red-200 text-red-700 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full"><i
                                    class="fa-solid fa-triangle-exclamation mr-0.5"></i> Critical</span>
                        @elseif($task->priority === 'high')
                            <span
                                class="bg-orange-50 border border-orange-200 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full">High</span>
                        @elseif($task->priority === 'low')
                            <span
                                class="bg-gray-50 border border-gray-200 text-gray-600 dark:bg-zinc-800/40 dark:text-zinc-400 dark:border-zinc-700/50 text-xs font-semibold px-2.5 py-0.5 rounded-full">Low</span>
                        @else
                            <span
                                class="bg-indigo-50 border border-indigo-200 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full">Medium</span>
                        @endif
                    </div>
                </div>

                <!-- Update status form -->
                @can('Task-ProgressUpdate')
                    @php
                        $userId = Auth::id();
                        $isDeveloper = $userId === $task->assigned_to;
                        $isTester = $userId === $task->tester_id;
                        $isAssigner = $userId === $task->assigned_by;
                        $isAdminOrManager =
                            Auth::user()->can('Task-ManageAll') ||
                            Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);

                        $isDropdownDisabled = false;
                        $allowedStatuses = [];

                        if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner) {
                            $isDropdownDisabled = true;
                        } else {
                            if ($isAdminOrManager || $isAssigner) {
                                $allowedStatuses = ['open', 'in_progress', 'testing', 'completed', 'closed'];
                            } elseif ($isDeveloper) {
                                $allowedStatuses = ['in_progress', 'testing'];
                                if (!in_array($task->status, $allowedStatuses)) {
                                    $allowedStatuses[] = $task->status;
                                }
                            } elseif ($isTester) {
                                if ($task->status !== 'testing') {
                                    $isDropdownDisabled = true;
                                } else {
                                    $allowedStatuses = ['completed', 'in_progress'];
                                    if (!in_array($task->status, $allowedStatuses)) {
                                        $allowedStatuses[] = $task->status;
                                    }
                                }
                            } else {
                                $isDropdownDisabled = true;
                            }
                        }
                    @endphp

                    <form action="{{ route('tasks.status.update', $task->id) }}" method="POST"
                        class="pt-3 border-t border-gray-100 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Change Task Status</label>
                            <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-xs {{ $isDropdownDisabled ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                required {{ $isDropdownDisabled ? 'disabled' : '' }}>
                                @if (in_array('open', $allowedStatuses))
                                    <option value="open" {{ $task->status === 'open' ? 'selected' : '' }}>Open</option>
                                @endif
                                @if (in_array('in_progress', $allowedStatuses))
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                @endif
                                @if (in_array('testing', $allowedStatuses))
                                    <option value="testing" {{ $task->status === 'testing' ? 'selected' : '' }}>Testing
                                    </option>
                                @endif
                                @if (in_array('completed', $allowedStatuses))
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                @endif
                                @if (in_array('closed', $allowedStatuses))
                                    <option value="closed" {{ $task->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                @endif
                            </select>
                        </div>

                        @if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner)
                            <div
                                class="bg-gray-50 border border-gray-250 text-gray-550 text-[10px] px-3 py-2 rounded-lg flex items-center gap-1.5 justify-center font-medium italic">
                                <i class="fa-solid fa-lock text-gray-450"></i> Reopening this task is restricted.
                            </div>
                        @endif

                        @if ($isTester && !$isDeveloper && !$isAdminOrManager && !$isAssigner && $task->status !== 'testing')
                            <div
                                class="bg-amber-50 border border-amber-250 text-amber-700 text-[10px] px-3 py-2 rounded-lg flex items-center gap-1.5 justify-center font-medium italic">
                                <i class="fa-solid fa-clock text-amber-500"></i> Waiting for worker
                            </div>
                        @endif

                        @if (!$isDropdownDisabled)
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status Change Remark /
                                    Note</label>
                                <textarea name="remark" rows="2" placeholder="Describe progress or reasons for status change..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-gray-400 bg-gray-50"></textarea>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-900 border border-gray-700 text-white py-2 rounded-lg text-xs font-bold transition-colors {{ $isDropdownDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $isDropdownDisabled ? 'disabled' : '' }}>Apply Status Change</button>
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

                    @php
                        $userId = Auth::id();
                        $isAdminOrManager =
                            Auth::user()->can('Task-ManageAll') ||
                            Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'HR Manager']);
                        $isAssigner = $userId === $task->assigned_by;
                    @endphp

                    @if ($task->status === 'closed' && !$isAdminOrManager && !$isAssigner)
                        <div
                            class="bg-gray-50 border border-gray-250 text-gray-500 text-xs px-4 py-3.5 rounded-xl flex items-center gap-2 justify-center font-medium italic">
                            <i class="fa-solid fa-lock text-gray-450"></i> File uploads are locked because this task is closed.
                        </div>
                    @else
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
                    @endif

                    <!-- list of uploaded documents -->
                    <div class="space-y-2.5 max-h-[220px] overflow-y-auto pr-1">
                        @forelse($task->documents as $doc)
                            <div
                                class="p-3 bg-white border border-gray-100 hover:border-gray-200 rounded-xl text-xs space-y-1.5 shadow-2xs hover:shadow-xs transition-shadow">
                                <div class="flex justify-between items-start gap-1">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                        class="text-indigo-650 hover:underline font-bold text-xs truncate flex-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-file text-[13px] text-red-500 flex-shrink-0"></i>
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

        </div>
    </div>
@endsection
