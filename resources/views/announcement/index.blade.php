@extends('layouts.main')

@section('title', 'Announcements | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Announcements</h1>
            <p class="text-sm text-gray-500 mt-1">Share company updates and news with your team</p>
        </div>
        @can('Announcement-Create')
            <x-create-button :url="route('announcements.create')" label="Add Announcement" />
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="announcements" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Title</th>
                        <th class="px-6 py-3 font-semibold">Created By</th>
                        <th class="px-6 py-3 font-semibold">Publish Date</th>
                        <th class="px-6 py-3 font-semibold">Priority</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($announcements as $key => $announcement)
                        <tr
                            class="transition-colors {{ $announcement->trashed() ? 'opacity-70' : 'hover:bg-gray-50' }}">
                            {{-- # --}}
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>

                            {{-- Title --}}
                            <td class="px-6 py-4">
                                @if (auth()->user()->can('Announcement-View'))
                                    <a href="{{ route('announcements.show', $announcement->id) }}">
                                        <p
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                            {{ $announcement->title }}</p>
                                    </a>
                                @else
                                    <p class="text-sm font-semibold text-gray-800">{{ $announcement->title }}</p>
                                @endif
                            </td>

                            {{-- Creator --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $announcement->creator->name ?? 'System' }}</p>
                            </td>

                            {{-- Publish Date --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800">
                                    {{ $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('l, d F Y') : $announcement->created_at->format('l, d F Y') }}
                                </p>
                            </td>

                            {{-- Priority --}}
                            <td class="px-6 py-4">
                                <x-badge :value="$announcement->priority" />
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                <x-badge :value="$announcement->status" />
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($announcement->trashed())
                                        @can('Announcement-Restore')
                                            <form action="{{ route('announcements.restore', $announcement->id) }}"
                                                method="POST" id="restore-form-{{ $announcement->id }}">
                                                @csrf
                                                <button type="button" title="Restore Announcement"
                                                    onclick="confirmRestore({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-rotate-left text-base"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('Announcement-Edit')
                                            <a href="{{ route('announcements.edit', $announcement->id) }}"
                                                title="Edit Announcement"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan
                                        @can('Announcement-Delete')
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}"
                                                method="POST" id="delete-form-{{ $announcement->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete Announcement"
                                                    onclick="confirmSoftDelete({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-trash-can text-base"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
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
        // ===== SOFT DELETE =====
        function confirmSoftDelete(id, title) {
            Swal.fire({
                title: 'Delete Announcement?',
                html: `<span class="text-gray-600">Are you sure you want to delete <strong>${title}</strong>?<br><span class="text-sm text-gray-400">It can be restored later.</span></span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // ===== RESTORE =====
        function confirmRestore(id, title) {
            Swal.fire({
                title: 'Restore Announcement?',
                html: `<span class="text-gray-600">Restore <strong>${title}</strong> back to active records?</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-rotate-left mr-1"></i> Yes, Restore',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + id).submit();
                }
            });
        }

        // ===== PERMANENT DELETE =====
        function confirmForceDelete(id, title) {
            Swal.fire({
                title: 'Permanently Delete?',
                html: `<span class="text-gray-600">This will <strong>permanently delete</strong> <strong>${title}</strong>.<br><br><span class="text-red-500 text-sm font-semibold">⚠️ This action CANNOT be undone.</span></span>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash-can mr-1"></i> Yes, Delete Forever',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
