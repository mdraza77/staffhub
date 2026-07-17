@extends('layouts.main')

@section('title', 'Defects Tracking | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Defects & Bug Tracking</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and track application defects, bug logs, and resolutions.</p>
        </div>

        @can('Defect-Create')
            <x-create-button :url="route('defects.create')" label="Report a Bug" />
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table id="defects_table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Bug ID</th>
                        <th class="px-6 py-3 font-semibold">Defect details</th>
                        <th class="px-6 py-3 font-semibold">Module</th>
                        <th class="px-6 py-3 font-semibold text-center">Severity</th>
                        <th class="px-6 py-3 font-semibold text-center">Priority</th>
                        <th class="px-6 py-3 font-semibold text-center">Assignee</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Deadline</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 font-semibold text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($defects as $defect)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-s">
                                @if (auth()->user()->can('Defect-View'))
                                    <a href="{{ route('defects.show', $defect->id) }}">
                                        <p class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                            {{ $defect->defect_id }}</p>
                                    </a>
                                @else
                                    <p class="text-sm font-medium text-gray-800">{{ $defect->defect_id }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($defect->project)
                                    <span
                                        class="bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded border border-indigo-150 mr-1.5 align-middle">
                                        {{ $defect->project->name }}{{ $defect->project->trashed() ? ' [Deleted]' : '' }}
                                    </span>
                                @endif
                                <p>{{ $defect->title }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">Reported by
                                    {{ $defect->reporter->name ?? 'User' }} &bull;
                                    {{ $defect->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <span class="font-medium text-gray-700">{{ $defect->module }}</span>
                                @if ($defect->sub_module)
                                    <span class="text-gray-400 block text-[10px]">{{ $defect->sub_module }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($defect->severity === 'critical')
                                    <span
                                        class="bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-red-200">Critical</span>
                                @elseif($defect->severity === 'high')
                                    <span
                                        class="bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-orange-200">High</span>
                                @elseif($defect->severity === 'medium')
                                    <span
                                        class="bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-blue-200">Medium</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-600 dark:bg-zinc-800/40 dark:text-zinc-400 dark:border-zinc-700/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-gray-200">Low</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($defect->priority === 'urgent')
                                    <span
                                        class="bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-red-200"><i
                                            class="fa-solid fa-triangle-exclamation mr-0.5"></i> Urgent</span>
                                @elseif($defect->priority === 'high')
                                    <span
                                        class="bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-orange-200">High</span>
                                @elseif($defect->priority === 'medium')
                                    <span
                                        class="bg-yellow-50 text-yellow-700 dark:bg-yellow-950/40 dark:text-yellow-400 dark:border-yellow-900/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-yellow-200">Medium</span>
                                @else
                                    <span
                                        class="bg-gray-50 text-gray-600 dark:bg-zinc-800/40 dark:text-zinc-400 dark:border-zinc-700/50 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase text-[10px] border border-gray-200">Low</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-700">
                                @if ($defect->assignee)
                                    <div class="flex items-center justify-center gap-1.5">
                                        <span class="font-medium">{{ $defect->assignee->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-700 whitespace-nowrap">
                                <span class="{{ $defect->deadline && $defect->deadline->isPast() && $defect->status !== 'closed' ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                    {{ $defect->deadline ? $defect->deadline->format('d M Y') : '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold whitespace-nowrap">
                                @if ($defect->trashed())
                                    <span
                                        class="bg-red-150 text-red-750 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-red-200">
                                        <i class="fa-solid fa-ban mr-0.5"></i> Deleted
                                    </span>
                                @elseif ($defect->status === 'closed')
                                    <span
                                        class="bg-gray-100 text-gray-700 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-gray-200">Closed</span>
                                @elseif($defect->status === 'ready_for_testing')
                                    <span
                                        class="bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-purple-200">Testing</span>
                                @elseif($defect->status === 'in_progress')
                                    <span
                                        class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-blue-200">In
                                        Progress</span>
                                @elseif($defect->status === 'reopened')
                                    <span
                                        class="bg-yellow-100 text-yellow-750 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-yellow-200">Reopened</span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase text-[10px] border border-green-200">Open</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if ($defect->trashed())
                                    @can('Defect-Restore')
                                        <form action="{{ route('defects.restore', $defect->id) }}" method="POST"
                                            id="restore-form-{{ $defect->id }}" class="inline">
                                            @csrf
                                            <button type="button"
                                                onclick="confirmRestore('{{ $defect->id }}', '{{ addslashes($defect->title) }}')"
                                                title="Restore Defect"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-rotate-left text-base"></i>
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    @can('Defect-Edit')
                                        <a href="{{ route('defects.edit', $defect->id) }}" title="Edit Defect"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="fa-solid fa-pen-to-square text-base"></i>
                                        </a>
                                    @endcan
                                    @can('Defect-Delete')
                                        <button type="button" onclick="confirmDelete('{{ $defect->id }}')"
                                            title="Archive Defect"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="fa-solid fa-trash-can text-base"></i>
                                        </button>
                                        <form id="delete-form-{{ $defect->id }}"
                                            action="{{ route('defects.destroy', $defect->id) }}" method="POST" class="hidden">
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
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to archive this defect?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        function confirmRestore(id, name) {
            Swal.fire({
                title: 'Restore Defect?',
                text: "Do you want to restore the defect: " + name + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
