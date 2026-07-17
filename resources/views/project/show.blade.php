@extends('layouts.main')

@section('title', $project->name . ' Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Project Details</h1>
            <p class="text-sm text-gray-500 mt-1">Timeline, resource allocation, task progress, and defect tracking.</p>
        </div>
        <div class="flex items-center gap-3">
            @can('Project-Index')
                <x-back-button :url="route('projects.index')" label="Back to Projects" />
            @endcan
            @if (!$project->trashed())
                @can('Project-Edit')
                    <a href="{{ route('projects.edit', $project->id) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Details
                    </a>
                @endcan
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Core Info & Timeline (Col Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Project Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if ($project->status === 'planning')
                            <span class="bg-blue-50 text-blue-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-blue-150 uppercase tracking-wider">Planning</span>
                        @elseif ($project->status === 'in_progress')
                            <span class="bg-amber-50 text-amber-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-amber-150 uppercase tracking-wider">In Progress</span>
                        @elseif ($project->status === 'on_hold')
                            <span class="bg-gray-50 text-gray-600 text-[10px] font-bold px-2.5 py-0.5 rounded border border-gray-200 uppercase tracking-wider">On Hold</span>
                        @elseif ($project->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-emerald-150 uppercase tracking-wider">Completed</span>
                        @elseif ($project->status === 'cancelled')
                            <span class="bg-rose-50 text-rose-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-rose-150 uppercase tracking-wider">Cancelled</span>
                        @endif
                        @if ($project->trashed())
                            <span class="bg-red-50 text-red-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-red-150 uppercase tracking-wider">Archived</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-850">{{ $project->name }}</h2>
                </div>

                @if ($project->description)
                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Project Description</h3>
                        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line bg-gray-50 p-4 border border-gray-150 rounded-xl">
                            {{ $project->description }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Tabbed Interface for Tasks & Defects -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" onclick="switchTab('tasks-tab', 'defects-tab', this)"
                            class="border-indigo-500 text-indigo-650 whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-1.5 focus:outline-none tab-btn">
                            <i class="fa-solid fa-list-check"></i> Tasks
                            <span class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-xs px-2 py-0.2 rounded-full font-bold ml-1">{{ $project->tasks->count() }}</span>
                        </button>
                        <button type="button" onclick="switchTab('defects-tab', 'tasks-tab', this)"
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-1.5 focus:outline-none tab-btn">
                            <i class="fa-solid fa-bug"></i> Defects
                            <span class="bg-rose-50 border border-rose-150 text-rose-700 text-xs px-2 py-0.2 rounded-full font-bold ml-1">{{ $project->defects->count() }}</span>
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Tasks Tab Panel -->
                    <div id="tasks-tab" class="tab-panel">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                                        <th class="px-4 py-2.5 font-semibold">Title</th>
                                        <th class="px-4 py-2.5 font-semibold text-center">Status</th>
                                        <th class="px-4 py-2.5 font-semibold text-center">Priority</th>
                                        <th class="px-4 py-2.5 font-semibold">Assignee</th>
                                        <th class="px-4 py-2.5 font-semibold text-center">Deadline</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($project->tasks as $task)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 font-semibold text-gray-800">
                                                <a href="{{ route('tasks.show', $task->id) }}" class="text-indigo-650 hover:underline">
                                                    {{ Str::limit($task->title, 45) }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($task->status === 'completed')
                                                    <span class="bg-emerald-50 text-emerald-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-emerald-100">Completed</span>
                                                @elseif ($task->status === 'in_progress')
                                                    <span class="bg-blue-50 text-blue-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-blue-100">In Progress</span>
                                                @elseif ($task->status === 'testing')
                                                    <span class="bg-amber-50 text-amber-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-amber-100">Testing</span>
                                                @elseif ($task->status === 'closed')
                                                    <span class="bg-gray-100 text-gray-500 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-gray-200">Closed</span>
                                                @else
                                                    <span class="bg-gray-50 text-gray-600 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-gray-200">Open</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($task->priority === 'critical')
                                                    <span class="bg-red-50 text-red-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-red-100">Critical</span>
                                                @elseif ($task->priority === 'high')
                                                    <span class="bg-orange-50 text-orange-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-orange-100">High</span>
                                                @elseif ($task->priority === 'medium')
                                                    <span class="bg-yellow-50 text-yellow-750 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-yellow-100">Medium</span>
                                                @else
                                                    <span class="bg-gray-50 text-gray-600 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-gray-200">Low</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $task->engineer->name ?? 'Unassigned' }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-500">
                                                {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : '—' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">No tasks mapped to this project yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Defects Tab Panel -->
                    <div id="defects-tab" class="tab-panel hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                                        <th class="px-4 py-2.5 font-semibold">Bug ID</th>
                                        <th class="px-4 py-2.5 font-semibold">Title</th>
                                        <th class="px-4 py-2.5 font-semibold text-center">Severity</th>
                                        <th class="px-4 py-2.5 font-semibold">Assignee</th>
                                        <th class="px-4 py-2.5 font-semibold text-center">Deadline</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($project->defects as $defect)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 font-mono font-bold text-indigo-650">
                                                <a href="{{ route('defects.show', $defect->id) }}" class="hover:underline">
                                                    {{ $defect->defect_id }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-gray-800 font-semibold">
                                                <a href="{{ route('defects.show', $defect->id) }}" class="hover:underline">
                                                    {{ Str::limit($defect->title, 40) }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($defect->severity === 'critical')
                                                    <span class="bg-red-50 text-red-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-red-100">Critical</span>
                                                @elseif ($defect->severity === 'high')
                                                    <span class="bg-orange-50 text-orange-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-orange-100">High</span>
                                                @elseif ($defect->severity === 'medium')
                                                    <span class="bg-blue-50 text-blue-700 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-blue-100">Medium</span>
                                                @else
                                                    <span class="bg-gray-50 text-gray-600 text-[9px] font-bold px-2 py-0.2 rounded uppercase border border-gray-200">Low</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $defect->assignee->name ?? 'Unassigned' }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-500">
                                                {{ $defect->deadline ? \Carbon\Carbon::parse($defect->deadline)->format('d M Y') : '—' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">No defects reported for this project.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline Metrics & Info Panel -->
        <div class="space-y-6">

            <!-- Metrics Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Project Properties</h3>

                <div class="space-y-3.5 text-xs text-gray-700">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Start Date:</span>
                        <span class="font-bold text-gray-800">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '—' }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">End Date:</span>
                        <span class="font-bold text-gray-800">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '—' }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                        <span class="text-gray-500">Active Tasks:</span>
                        <span class="font-bold text-indigo-750">
                            {{ $project->tasks->whereNotIn('status', ['completed', 'closed'])->count() }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Active Defects:</span>
                        <span class="font-bold text-rose-750">
                            {{ $project->defects->whereNotIn('status', ['closed'])->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tasks Completion Chart / Metric Card -->
            {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center text-center space-y-3">
                @php
                    $total = $project->tasks->count();
                    $completed = $project->tasks->where('status', 'completed')->count();
                    $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                @endphp
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Completion Rate</h3>
                
                <div class="relative flex items-center justify-center h-28 w-28">
                    <!-- Progress Circle -->
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="56" cy="56" r="48" stroke="#f3f4f6" stroke-width="8" fill="transparent"/>
                        <circle cx="56" cy="56" r="48" stroke="#6366f1" stroke-width="8" fill="transparent"
                                stroke-dasharray="301.6" stroke-dashoffset="{{ 301.6 - (301.6 * $percentage) / 100 }}"/>
                    </svg>
                    <span class="absolute text-xl font-extrabold text-indigo-650">{{ $percentage }}%</span>
                </div>

                <p class="text-xs text-gray-500 font-medium">
                    {{ $completed }} of {{ $total }} Tasks Finished
                </p>
            </div> --}}

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(showId, hideId, element) {
            // Panels toggle
            document.getElementById(showId).classList.remove('hidden');
            document.getElementById(hideId).classList.add('hidden');

            // Tabs classes toggle
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-650', 'font-bold');
                btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
            });

            element.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
            element.classList.add('border-indigo-500', 'text-indigo-650', 'font-bold');
        }
    </script>
@endpush
