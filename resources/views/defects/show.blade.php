@extends('layouts.main')

@section('title', 'Defect Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Defect Details</h1>
            <p class="text-sm text-gray-500 mt-1">Review bug specifications, reproduction logs, and change histories.</p>
        </div>
        <div class="flex items-center gap-3">
            @can('Defect-Index')
                <x-back-button :url="route('defects.index')" label="Back to Defects" />
            @endcan
            @if (!$defect->trashed())
                @can('Defect-Edit')
                    <a href="{{ route('defects.edit', $defect->id) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Details
                    </a>
                @endcan
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Core Description & Steps & Logs (Col Span 2) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Core Meta Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span
                            class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-[10px] font-mono font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            {{ $defect->defect_id }}
                        </span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                            {{ $defect->environment }}
                        </span>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-850">{{ $defect->title }}</h2>
                    <p class="text-xs text-gray-400 mt-1">
                        Reported by <span class="font-semibold text-gray-600">{{ $defect->reporter->name ?? 'User' }}</span>
                        &bull; {{ $defect->created_at->format('d M Y, h:i A') }}
                        ({{ $defect->created_at->diffForHumans() }})
                    </p>
                </div>

                {{-- Detailed description --}}
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="text-xs font-bold text-gray-700 mb-2.5 uppercase tracking-wide">Bug Description</h3>
                    <div
                        class="bg-gray-50 border border-gray-150 p-5 rounded-xl text-sm leading-relaxed text-gray-750 prose prose-sm max-w-none">
                        {!! $defect->description !!}
                    </div>
                </div>

                {{-- Steps to reproduce --}}
                @if ($defect->steps_to_reproduce)
                    <div class="border-t border-gray-100 pt-5">
                        <h3 class="text-xs font-bold text-gray-700 mb-2.5 uppercase tracking-wide">Steps to Reproduce</h3>
                        <div
                            class="bg-gray-50 border border-gray-150 p-5 rounded-xl text-sm leading-relaxed text-gray-750 prose prose-sm max-w-none">
                            {!! $defect->steps_to_reproduce !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Activity Logs / Status histories (JIRA / GitHub style) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-indigo-650"></i> Status Timeline History
                    <span
                        class="bg-gray-100 text-gray-655 text-xs px-2 py-0.5 rounded-full">{{ $defect->histories->count() }}</span>
                </h3>

                @if ($defect->histories->count() > 0)
                    <div class="relative border-l-2 border-indigo-150 pl-6 ml-4 space-y-6 py-2">
                        @foreach ($defect->histories as $history)
                            <div class="relative text-xs">
                                {{-- Timeline Dot --}}
                                <div
                                    class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-white border-2 border-indigo-500 flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-right-arrow-left text-[8px] text-indigo-500"></i>
                                </div>

                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between gap-1 text-[11px] text-gray-400">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-gray-700 text-sm">{{ $history->user->name ?? 'System' }}</span>
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
                                    @if ($history->old_status)
                                        <span
                                            class="bg-gray-100 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-full font-semibold uppercase text-[10px]">
                                            {{ str_replace('_', ' ', $history->old_status) }}
                                        </span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-full uppercase text-[10px] italic">None</span>
                                    @endif
                                    to
                                    <span
                                        class="bg-indigo-50 text-indigo-750 border border-indigo-100 px-2 py-0.5 rounded-full font-bold uppercase text-[10px]">
                                        {{ str_replace('_', ' ', $history->new_status) }}
                                    </span>
                                </p>

                                @if ($history->remark)
                                    <div
                                        class="mt-3 p-3.5 bg-gray-50 border border-gray-150 rounded-xl text-gray-700 leading-relaxed max-w-3xl shadow-3xs">
                                        <div
                                            class="flex items-center gap-1 mb-1.5 text-[9px] font-bold uppercase text-gray-400 tracking-wider">
                                            <i class="fa-solid fa-comment-dots text-indigo-400 text-xs"></i>
                                            Change Remark
                                        </div>
                                        <p class="font-light text-xs whitespace-pre-line">{{ $history->remark }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">No transition changes logged for this bug.</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Properties & Status update & Attachments (Col Span 1) -->
        <div class="space-y-6">

            <!-- Properties Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Properties & Metrics</h3>

                <div class="space-y-3.5 text-xs text-gray-700">
                    @if ($defect->project)
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                            <span class="text-gray-500">Project:</span>
                            <span class="font-bold text-gray-800">{{ $defect->project->name }}{{ $defect->project->trashed() ? ' [Deleted]' : '' }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Severity:</span>
                        @if ($defect->severity === 'critical')
                            <span
                                class="bg-red-50 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-red-100">Critical</span>
                        @elseif($defect->severity === 'high')
                            <span
                                class="bg-orange-50 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-orange-100">High</span>
                        @elseif($defect->severity === 'medium')
                            <span
                                class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-blue-100">Medium</span>
                        @else
                            <span
                                class="bg-gray-50 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-gray-200">Low</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Priority:</span>
                        @if ($defect->priority === 'urgent')
                            <span
                                class="bg-red-50 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-red-100">Urgent</span>
                        @elseif($defect->priority === 'high')
                            <span
                                class="bg-orange-50 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-orange-100">High</span>
                        @elseif($defect->priority === 'medium')
                            <span
                                class="bg-yellow-50 text-yellow-750 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-yellow-100">Medium</span>
                        @else
                            <span
                                class="bg-gray-50 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-gray-200">Low</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Module:</span>
                        <span class="font-semibold text-gray-800">{{ $defect->module }}</span>
                    </div>

                    @if ($defect->sub_module)
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                            <span class="text-gray-500">Sub-Module:</span>
                            <span class="font-semibold text-gray-850">{{ $defect->sub_module }}</span>
                        </div>
                    @endif

                    @if ($defect->browser_os)
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                            <span class="text-gray-500">Client / Browser:</span>
                            <span class="font-medium text-gray-600 text-right">{{ $defect->browser_os }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Assignee:</span>
                        <span class="font-bold text-indigo-650">{{ $defect->assignee->name ?? 'Unassigned' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Reporter:</span>
                        <span class="font-semibold text-gray-700">{{ $defect->reporter->name ?? 'User' }}</span>
                    </div>
                </div>
            </div>

            <!-- Change Status Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Change Status</h3>

                @php
                    $isStatusClosed = $defect->status === 'closed';
                    $user = Auth::user();
                    $isAdminOrManager =
                        $user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('HR Manager');
                    $isAssignee = $user->id === $defect->assigned_to;
                    $isReporter = $user->id === $defect->reported_by;

                    // Reopening or updating closed defect is blocked for regular users unless they reported it or are admin
                    $isActionDisabled = false;
                    if ($isStatusClosed && !$isAdminOrManager && !$isReporter) {
                        $isActionDisabled = true;
                    }
                @endphp

                @if ($defect->trashed())
                    <div
                        class="bg-gray-50 border border-gray-250 text-gray-550 text-[10px] px-3 py-2 rounded-lg flex items-center gap-2 justify-center font-medium italic">
                        <i class="fa-solid fa-lock text-gray-450"></i> Status changes are locked because this defect is deleted.
                    </div>
                @elseif ($isActionDisabled)
                    <div
                        class="bg-gray-50 border border-gray-250 text-gray-550 text-[10px] px-3 py-2 rounded-lg flex items-center gap-2 justify-center font-medium italic">
                        <i class="fa-solid fa-lock text-gray-450"></i> Reopening this defect is restricted.
                    </div>
                @else
                    <form action="{{ route('defects.status.update', $defect->id) }}" method="POST" class="space-y-3 pt-1">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status</label>
                            <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-xs"
                                required>
                                <option value="open" {{ $defect->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $defect->status === 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="ready_for_testing" {{ $defect->status === 'ready_for_testing' ? 'selected' : '' }}>
                                    Ready For Testing
                                </option>
                                <option value="closed" {{ $defect->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="reopened" {{ $defect->status === 'reopened' ? 'selected' : '' }}>Reopened
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Change Remark</label>
                            <textarea name="remark" rows="2" placeholder="Provide details for this status transition..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-gray-400 bg-gray-50"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                            Apply Transition
                        </button>
                    </form>
                @endif
            </div>

            <!-- Upload Attachments Form & Files list -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center justify-between">
                    <span><i class="fa-solid fa-paperclip text-teal-600 mr-1"></i> Attachments</span>
                    <span
                        class="bg-teal-50 border border-teal-100 text-teal-700 text-xs px-2 py-0.5 rounded-full">{{ $defect->attachments->count() }}</span>
                </h3>

                @if ($defect->trashed())
                    <div
                        class="bg-gray-50 border border-gray-250 text-gray-550 text-xs px-4 py-3.5 rounded-xl flex items-center gap-2 justify-center font-medium italic">
                        <i class="fa-solid fa-lock text-gray-455"></i> Uploads are locked because this defect is deleted.
                    </div>
                @elseif ($isStatusClosed)
                    <div
                        class="bg-gray-50 border border-gray-250 text-gray-550 text-xs px-4 py-3.5 rounded-xl flex items-center gap-2 justify-center font-medium italic">
                        <i class="fa-solid fa-lock text-gray-450"></i> Uploads are locked on closed defects.
                    </div>
                @else
                    <!-- Upload Form -->
                    <form action="{{ route('defects.attachments.store', $defect->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-3 p-3 bg-gray-50 border border-gray-200 rounded-xl">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Choose File</label>
                            <input type="file" name="file"
                                class="w-full text-xs text-gray-700 border border-gray-300 bg-white rounded-lg px-2 py-1.5 focus:ring-1 focus:ring-indigo-500"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1 shadow-sm">
                            <i class="fa-solid fa-upload"></i> Upload File
                        </button>
                    </form>
                @endif

                <!-- Files list -->
                <div class="space-y-2.5 max-h-[240px] overflow-y-auto pr-1">
                    @forelse($defect->attachments as $attach)
                        <div
                            class="p-3 bg-white border border-gray-100 hover:border-gray-200 rounded-xl text-xs space-y-1.5 shadow-2xs hover:shadow-xs transition-shadow">
                            <div class="flex justify-between items-start gap-1">
                                <a href="{{ str_starts_with($attach->file_path, 'http') ? $attach->file_path : asset('storage/' . $attach->file_path) }}"
                                    target="_blank"
                                    class="text-indigo-650 hover:underline font-bold text-xs truncate flex-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-image text-[13px] text-blue-500 flex-shrink-0"></i>
                                    {{ $attach->file_name }}
                                </a>
                                <span class="text-[9px] text-gray-400 font-medium font-mono ml-auto">{{ $attach->file_size }}
                                    KB</span>
                            </div>
                            <div
                                class="flex justify-between items-center text-[10px] text-gray-405 border-t border-gray-50 pt-1.5">
                                <span>By {{ $attach->uploader->name ?? 'User' }}</span>
                                <span>{{ $attach->created_at->format('d M, h:i A') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-xs italic">
                            No files attached to this bug report.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmRestore(id) {
            Swal.fire({
                title: "Restore Defect?",
                text: "This defect will be restored to active status.",
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