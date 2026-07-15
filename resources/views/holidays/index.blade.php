@extends('layouts.main')

@section('title', 'Holidays | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Holidays</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your workforce</p>
        </div>
        @can('Holiday-Create')
            <a href="{{ route('holidays.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add Holidays
            </a>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="holidays" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Name</th>
                        <th class="px-6 py-3 font-semibold">Start & End Date</th>
                        <th class="px-6 py-3 font-semibold">Type</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($holidays as $key => $holiday)
                        <tr
                            class="transition-colors {{ $holiday->trashed() ? 'opacity-70' : 'hover:bg-gray-50' }}">

                            {{-- # --}}
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>

                            {{-- Name --}}
                            <td class="px-6 py-4">
                                @if (auth()->user()->can('Holiday-View'))
                                    <a href="{{ route('holidays.show', $holiday->id) }}">
                                        <p class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                            {{ $holiday->name }}</p>
                                    </a>
                                @else
                                    <p class="text-sm font-semibold text-gray-800">{{ $holiday->name }}</p>
                                @endif
                            </td>

                            {{-- start date and end date --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $holiday->start_date ? \Carbon\Carbon::parse($holiday->start_date)->format('d M Y') : 'N/A' }}
                                    @if ($holiday->end_date && $holiday->end_date->ne($holiday->start_date))
                                        - {{ \Carbon\Carbon::parse($holiday->end_date)->format('d M Y') }}
                                    @endif
                                </p>
                            </td>

                            {{-- Type --}}
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                                    {{ $holiday->type === 'public' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                    {{ $holiday->type === 'optional' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                    {{ $holiday->type === 'company' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                                ">
                                    {{ $holiday->type }}
                                </span>
                            </td>

                            {{-- Description --}}
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $holiday->description ?? '—' }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if ($holiday->trashed())
                                    <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-ban text-[10px] mr-1"></i>Deleted
                                    </span>
                                @elseif ($holiday->status === 'active')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Active
                                    </span>
                                @elseif ($holiday->status === 'inactive')
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Inactive
                                    </span>
                                    {{-- @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Terminated
                                    </span> --}}
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($holiday->trashed())
                                        {{-- SOFT DELETED STATE: Restore + Force Delete only --}}

                                        {{-- Restore Form --}}
                                        @can('Holiday-Restore')
                                            <form action="{{ route('holidays.restore', $holiday->id) }}" method="POST"
                                                id="restore-form-{{ $holiday->id }}">
                                                @csrf
                                                <button type="button" title="Restore holiday"
                                                    onclick="confirmRestore({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-rotate-left text-base"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        {{-- Permanent Delete Form --}}
                                        @can('Holiday-ForceDelete')
                                            <form action="{{ route('holidays.force-delete', $holiday->id) }}" method="POST"
                                                id="force-delete-form-{{ $holiday->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Permanently Delete"
                                                    onclick="confirmForceDelete({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-trash-can text-base"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        {{-- NORMAL STATE: View + Edit + Soft Delete --}}

                                        {{-- View --}}
                                        {{-- @can('Holiday-View')
                                            <a href="{{ route('holidays.show', $holiday->id) }}" title="View Details"
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-eye text-base"></i>
                                            </a>
                                        @endcan --}}

                                        {{-- Edit --}}
                                        @can('Holiday-Edit')
                                            <a href="{{ route('holidays.edit', $holiday->id) }}" title="Edit holiday"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan

                                        {{-- Soft Delete Form --}}
                                        @can('Holiday-Delete')
                                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                                id="delete-form-{{ $holiday->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete holiday"
                                                    onclick="confirmSoftDelete({{ $holiday->id }}, '{{ addslashes($holiday->name) }}')"
                                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-trash text-base"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>

                        </tr>
                        {{-- @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
                                No holidays found. Click "Add holiday" to get started.
                            </td>
                        </tr> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

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
                html: `<span class="text-gray-600">This will <strong>permanently delete</strong> <strong>${name}</strong> along with their profile picture.<br><br><span class="text-red-500 text-sm font-semibold">⚠️ This action CANNOT be undone.</span></span>`,
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

    <script>
        $(document).ready(function() {
            $('#holidays').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
                buttons: [{
                        extend: 'copy',
                        className: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 mr-2 transition-colors'
                    },
                    {
                        extend: 'excel',
                        className: 'bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'pdf',
                        className: 'bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'print',
                        className: 'bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 transition-colors'
                    }
                ],
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search here...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });
    </script>
@endpush
