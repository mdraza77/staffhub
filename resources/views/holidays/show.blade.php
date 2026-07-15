@extends('layouts.main')

@section('title', 'Holiday Details | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Holiday Details</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed calendar event information</p>
        </div>
        <div class="flex items-center gap-3">
            @if (!$holiday->trashed())
                @can('holiday-Edit')
                    <a href="{{ route('holidays.edit', $holiday->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endcan
            @endif
            <x-back-button :url="route('holidays.index')" label="Back to Holidays" />
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 md:p-8 max-w-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $holiday->name }}</h2>
            <div>
                @if ($holiday->trashed())
                    <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-ban text-[10px] mr-1"></i> Deleted
                    </span>
                @elseif ($holiday->status === 'active')
                    <span
                        class="bg-green-50 text-green-700 border border-green-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-circle text-green-500 text-[8px] mr-1"></i> Active
                    </span>
                @else
                    <span
                        class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-full">
                        <i class="fa-solid fa-circle text-yellow-500 text-[8px] mr-1"></i> Inactive
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Start Date</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-regular fa-calendar-check text-blue-500 mr-1.5"></i>
                    {{ $holiday->start_date ? $holiday->start_date->format('l, d F Y') : 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">End Date</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-regular fa-calendar-times text-purple-500 mr-1.5"></i>
                    {{ $holiday->end_date ? $holiday->end_date->format('l, d F Y') : 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Holiday Type</p>
                <p class="mt-1 text-sm font-medium text-gray-800 uppercase">
                    <span class="px-2.5 py-1 rounded bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                        {{ $holiday->type }}
                    </span>
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Duration</p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    <i class="fa-solid fa-hourglass-half text-amber-500 mr-1.5"></i>
                    @if ($holiday->end_date)
                        {{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }}
                        {{ Str::plural('Day', $holiday->start_date->diffInDays($holiday->end_date) + 1) }}
                    @else
                        1 Day
                    @endif
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Description</p>
                <div class="mt-2 text-sm text-gray-700 bg-gray-50 border border-gray-100 p-4 rounded-lg leading-relaxed">
                    {{ $holiday->description ?? 'No description provided for this holiday.' }}
                </div>
            </div>
        </div>

        @if ($holiday->trashed())
            <div class="mt-8 border-t border-gray-100 pt-6 flex justify-end gap-3">
                @can('holiday-Restore')
                    <form action="{{ route('holidays.restore', $holiday->id) }}" method="POST" id="restore-form-{{ $holiday->id }}">
                        @csrf
                        <button type="button" onclick="confirmRestore({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-rotate-left"></i> Restore
                        </button>
                    </form>
                @endcan

                @can('holiday-ForceDelete')
                    <form action="{{ route('holidays.force-delete', $holiday->id) }}" method="POST"
                        id="force-delete-form-{{ $holiday->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmForceDelete({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-trash-can"></i> Permanently Delete
                        </button>
                    </form>
                @endcan
            </div>
        @else
            @can('holiday-Delete')
                <div class="mt-8 border-t border-gray-100 pt-6 flex justify-end">
                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" id="delete-form-{{ $holiday->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmSoftDelete({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                            class="px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium text-sm flex items-center gap-2">
                            <i class="fa-solid fa-trash"></i> Delete Holiday
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
            function confirmSoftDelete(id, name) {
                Swal.fire({
                    title: 'Delete holiday?',
                    html: `<span class="text-gray-600">Are you sure you want to delete <strong>${name}</strong>?<br><span class="text-sm text-gray-400">They can be restored later.</span></span>`,
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
            function confirmRestore(id, name) {
                Swal.fire({
                    title: 'Restore holiday?',
                    html: `<span class="text-gray-600">Restore <strong>${name}</strong> back to active records?</span>`,
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
            function confirmForceDelete(id, name) {
                Swal.fire({
                    title: 'Permanently Delete?',
                    html: `<span class="text-gray-600">This will <strong>permanently delete</strong> <strong>${name}</strong>.<br><br><span class="text-red-500 text-sm font-semibold">⚠️ This action CANNOT be undone.</span></span>`,
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