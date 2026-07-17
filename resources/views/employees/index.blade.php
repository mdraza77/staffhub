@extends('layouts.main')

@section('title', 'Employees | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employees</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your workforce</p>
        </div>
        @can('Employee-Create')
            <x-create-button :url="route('employees.create')" label="Add Employee" />
        @endcan
    </div>


    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="employees" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">Emp ID & Designation</th>
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
                                        <img src="{{ $employee->profile }}" alt="{{ $employee->name }}"
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
                                            @if (auth()->user()->can('Employee-View'))
                                                <a href="{{ route('employees.show', $employee->id) }}">
                                                    <p
                                                        class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                                        {{ $employee->name }}
                                                    </p>
                                                </a>
                                            @else
                                                <span class="text-sm font-medium text-gray-800">
                                                    {{ $employee->name }}
                                                </span>
                                            @endif
                                            @if ($employee->trashed())
                                                <span
                                                    class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-600 tracking-wide">
                                                    Deleted
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Employee ID & Designation --}}
                            <td class="px-6 py-4">
                                @if (auth()->user()->can('Employee-View'))
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                        {{ $employee->employee_id ?? '--' }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $employee->employee_id ?? '--' }}
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500">{{ $employee->designation ?? 'N/A' }}</p>
                            </td>

                            {{-- Department --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $employee->department->name ?? 'Unassigned' }}
                            </td>

                            {{-- Role --}}
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
                                <x-badge :value="$employee->status" />
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($employee->trashed())

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
                                    @else
                                        @if (!$employee->hasRole('Super Admin'))
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
                                                        <i class="fa-solid fa-trash-can text-base"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            <span class="text-xs text-gray-400 font-semibold italic flex items-center gap-1">
                                                <i class="fa-solid fa-lock text-[10px]"></i> Locked
                                            </span>
                                        @endif
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
@endpush