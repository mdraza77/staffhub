@extends('layouts.main')

@section('title', 'Employees | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employees</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your workforce</p>
        </div>
        @can('Employee-Create')
            <a href="{{ route('employees.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add Employee
            </a>
        @endcan
    </div>

    {{-- Flash Messages --}}
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="employees" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">ID & Designation</th>
                        <th class="px-6 py-3 font-semibold">Department</th>
                        <th class="px-6 py-3 font-semibold">Role</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($employees as $key => $employee)
                        <tr class="transition-colors {{ $employee->trashed() ? 'opacity-70' : 'hover:bg-gray-50' }}">

                            {{-- # --}}
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>

                            {{-- Employee --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($employee->profile)
                                        <img src="{{ asset('storage/' . $employee->profile) }}" alt="{{ $employee->name }}"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200 {{ $employee->trashed() ? 'grayscale' : '' }}">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border
                                            {{ $employee->trashed() ? 'bg-gray-200 text-gray-400 border-gray-300' : 'bg-blue-100 text-blue-600 border-blue-200' }}">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-semibold text-gray-800">{{ $employee->name }}</p>
                                            @if ($employee->trashed())
                                                <span
                                                    class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-600 uppercase tracking-wide">
                                                    Deleted
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- ID & Designation --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('employees.show', $employee->id) }}">
                                    <p class="text-sm font-medium text-gray-800">{{ $employee->employee_id ?? 'N/A' }}</p>
                                </a>
                                <p class="text-xs text-gray-500">{{ $employee->designation ?? 'N/A' }}</p>
                            </td>

                            {{-- Department --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $employee->department->name ?? 'Unassigned' }}
                            </td>

                            {{-- ROLE --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if ($employee->roles->isNotEmpty())
                                    <span class="px-2 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-600">
                                        {{ $employee->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Unassigned</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if ($employee->trashed())
                                    <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-ban text-[10px] mr-1"></i>Deleted
                                    </span>
                                @elseif ($employee->status === 'active')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Active
                                    </span>
                                @elseif ($employee->status === 'inactive')
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Inactive
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Terminated
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($employee->trashed())
                                        {{-- SOFT DELETED STATE: Restore + Force Delete only --}}

                                        {{-- Restore Form --}}
                                        @can('Employee-Restore')
                                            <form action="{{ route('employees.restore', $employee->id) }}" method="POST"
                                                id="restore-form-{{ $employee->id }}">
                                                @csrf
                                                <button type="button" title="Restore Employee"
                                                    onclick="confirmRestore({{ $employee->id }}, '{{ addslashes($employee->name) }}')"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-rotate-left text-base"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        {{-- Permanent Delete Form --}}
                                        @can('Employee-ForceDelete')
                                            <form action="{{ route('employees.force-delete', $employee->id) }}" method="POST"
                                                id="force-delete-form-{{ $employee->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Permanently Delete"
                                                    onclick="confirmForceDelete({{ $employee->id }}, '{{ addslashes($employee->name) }}')"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-trash-can text-base"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        {{-- NORMAL STATE: View + Edit + Soft Delete --}}

                                        {{-- View --}}
                                        @can('Employee-View')
                                            <a href="{{ route('employees.show', $employee->id) }}" title="View Details"
                                                class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-eye text-base"></i>
                                            </a>
                                        @endcan

                                        {{-- Edit --}}
                                        @can('Employee-Edit')
                                            <a href="{{ route('employees.edit', $employee->id) }}" title="Edit Employee"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @endcan

                                        {{-- Soft Delete Form --}}
                                        @can('Employee-Delete')
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                                id="delete-form-{{ $employee->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete Employee"
                                                    onclick="confirmSoftDelete({{ $employee->id }}, '{{ addslashes($employee->name) }}')"
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
                                No employees found. Click "Add Employee" to get started.
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
                title: 'Delete Employee?',
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
                title: 'Restore Employee?',
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
            $('#employees').DataTable({
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
