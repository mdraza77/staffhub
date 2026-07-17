@extends('layouts.main')

@section('title', 'Projects | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Projects</h1>
            <p class="text-sm text-gray-500 mt-1">Manage software and operational project portfolios.</p>
        </div>
        @can('Project-Create')
            <x-create-button :url="route('projects.create')" label="New Project" />
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table id="projects_table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Project Name</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Start Date</th>
                        <th class="px-6 py-3 font-semibold text-center">End Date</th>
                        <th class="px-6 py-3 font-semibold text-center">Tasks</th>
                        <th class="px-6 py-3 font-semibold text-center">Defects</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($projects as $key => $project)
                        <tr class="hover:bg-gray-50 transition-colors {{ $project->trashed() ? 'opacity-70' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $key + 1 }}</td>
                            <td class="px-6 py-4">
                                @if (auth()->user()->can('Project-View'))
                                    <a href="{{ route('projects.show', $project->id) }}">
                                        <p class="text-sm font-semibold text-indigo-600 hover:underline">
                                            {{ $project->name }}
                                        </p>
                                    </a>
                                @else
                                    <p class="text-sm font-semibold text-gray-800">{{ $project->name }}</p>
                                @endif
                                @if ($project->description)
                                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-1 max-w-sm">{{ $project->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($project->status === 'planning')
                                    <span
                                        class="bg-blue-50 text-blue-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-blue-150 uppercase tracking-wider">Planning</span>
                                @elseif ($project->status === 'in_progress')
                                    <span
                                        class="bg-amber-50 text-amber-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-amber-150 uppercase tracking-wider">In
                                        Progress</span>
                                @elseif ($project->status === 'on_hold')
                                    <span
                                        class="bg-gray-50 text-gray-600 text-[10px] font-bold px-2.5 py-0.5 rounded border border-gray-200 uppercase tracking-wider">On
                                        Hold</span>
                                @elseif ($project->status === 'completed')
                                    <span
                                        class="bg-emerald-50 text-emerald-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-emerald-150 uppercase tracking-wider">Completed</span>
                                @elseif ($project->status === 'cancelled')
                                    <span
                                        class="bg-rose-50 text-rose-750 text-[10px] font-bold px-2.5 py-0.5 rounded border border-rose-150 uppercase tracking-wider">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-600 font-medium">
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-600 font-medium">
                                {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full border border-indigo-100">
                                    {{ $project->tasks_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-rose-50 text-rose-700 text-xs font-bold px-2 py-0.5 rounded-full border border-rose-100">
                                    {{ $project->defects_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($project->trashed())
                                        @can('Project-Restore')
                                            <button type="button"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="Restore" onclick="confirmRestore({{ $project->id }})">
                                                <i class="fa-solid fa-rotate-left text-lg"></i>
                                            </button>
                                            <form id="restore-form-{{ $project->id }}"
                                                action="{{ route('projects.restore', $project->id) }}" method="POST" class="hidden">
                                                @csrf
                                            </form>
                                        @endcan
                                    @else
                                        @can('Project-Edit')
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-lg"></i>
                                            </a>
                                        @endcan
                                        @can('Project-Delete')
                                            <button type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete" onclick="confirmDelete({{ $project->id }})">
                                                <i class="fa-solid fa-trash-can text-lg"></i>
                                            </button>
                                            <form id="delete-form-{{ $project->id }}"
                                                action="{{ route('projects.destroy', $project->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                No projects recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Archive Project?",
                text: "This will delete the project record.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, archive it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function confirmRestore(id) {
            Swal.fire({
                title: "Restore Project?",
                text: "The project record will be restored.",
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