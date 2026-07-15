@extends('layouts.main')

@section('title', 'Announcement Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Announcement Details</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed company update information</p>
        </div>
        <div class="flex items-center gap-3">
            @if (!$announcement->trashed())
                @can('Announcement-Edit')
                    <a href="{{ route('announcements.edit', $announcement->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
            @endif
            <x-back-button :url="route('announcements.index')" label="Back to Announcements" />
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 md:p-8 max-w-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $announcement->title }}</h2>
            <div>
                @if ($announcement->trashed())
                    <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-ban text-[10px] mr-1"></i> Deleted
                    </span>
                @elseif ($announcement->status === 'published')
                    <span
                        class="bg-green-50 text-green-700 border border-green-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-circle text-green-500 text-[8px] mr-1"></i> Published
                    </span>
                @else
                    <span
                        class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-circle text-yellow-500 text-[8px] mr-1"></i> Draft
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Publish Date</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>
                    {{ $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('l, d F Y') : $announcement->created_at->format('l, d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Priority</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <span class="px-2.5 py-1 rounded text-xs font-bold border uppercase
                                {{ $announcement->priority === 'high' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                {{ $announcement->priority === 'medium' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                {{ $announcement->priority === 'low' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                            ">
                        {{ $announcement->priority }}
                    </span>
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Created By</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-solid fa-user text-gray-500 mr-1.5"></i>
                    {{ $announcement->creator->name ?? 'System' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Created At</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-regular fa-clock text-gray-500 mr-1.5"></i>
                    {{ $announcement->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Message</p>
                <div
                    class="mt-2 text-sm text-gray-700 bg-gray-50 border border-gray-100 p-4 rounded-lg leading-relaxed whitespace-pre-wrap">
                    {{ $announcement->message }}
                </div>
            </div>
        </div>

        @if ($announcement->trashed())
            <div class="mt-8 border-t border-gray-100 pt-6 flex justify-end gap-3">
                @can('Announcement-Restore')
                    <form action="{{ route('announcements.restore', $announcement->id) }}" method="POST"
                        id="restore-form-{{ $announcement->id }}">
                        @csrf
                        <button type="button"
                            onclick="confirmRestore({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-rotate-left"></i> Restore
                        </button>
                    </form>
                @endcan

                @can('Announcement-ForceDelete')
                    <form action="{{ route('announcements.force-delete', $announcement->id) }}" method="POST"
                        id="force-delete-form-{{ $announcement->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="confirmForceDelete({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-trash-can"></i> Permanently Delete
                        </button>
                    </form>
                @endcan
            </div>
        @else
            @can('Announcement-Delete')
                <div class="mt-8 border-t border-gray-100 pt-6 flex justify-end">
                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                        id="delete-form-{{ $announcement->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="confirmSoftDelete({{ $announcement->id }}, '{{ addslashes($announcement->title) }}')"
                            class="px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium text-sm flex items-center gap-2">
                            <i class="fa-solid fa-trash"></i> Delete Announcement
                        </button>
                    </form>
                </div>
            @endcan
        @endif
    </div>

    {{-- Javascript actions --}}
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
@endsection