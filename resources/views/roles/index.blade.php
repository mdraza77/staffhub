@extends('layouts.main')

@section('title', 'Roles & Permissions | StaffHub')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
            <p class="text-sm text-gray-500 mt-1">Manage access control for your team</p>
        </div>
        @can('AccessManagement-Create')
            <x-create-button :url="route('roles.create')" label="Add Role" />
        @endcan
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="role" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Role Name</th>
                        <th class="px-6 py-3 font-semibold">Permissions</th>
                        <th class="px-6 py-3 font-semibold">Created</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($roles as $index => $role)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- # --}}
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                            {{-- Role Name --}}
                            <td class="px-6 py-4">
                                @if (auth()->user()->can('AccessManagement-View'))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <i class="fa-solid fa-shield-halved text-indigo-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('roles.show', $role->id) }}">
                                                <p
                                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                                    {{ $role->name }}
                                                </p>
                                            </a>
                                            @if ($role->name === 'Super Admin')
                                                <span
                                                    class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-600 uppercase tracking-wide">
                                                    System Role
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <i class="fa-solid fa-shield-halved text-indigo-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $role->name }}</p>
                                            @if ($role->name === 'Super Admin')
                                                <span
                                                    class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-600 uppercase tracking-wide">
                                                    System Role
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Permissions Count --}}
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fa-solid fa-key text-[10px]"></i>
                                    {{ $role->permissions_count }}
                                    {{ Str::plural('permission', $role->permissions_count) }}
                                </span>
                            </td>

                            {{-- Created At --}}
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $role->created_at->format('d M, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">

                                    {{-- Edit --}}
                                    @can('AccessManagement-Edit')
                                        @if ($role->name !== 'Super Admin')
                                            <a href="{{ route('roles.edit', $role->id) }}" title="Edit Role"
                                                class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </a>
                                        @else
                                            <span class="p-2 text-gray-200 cursor-not-allowed" title="Cannot edit System Role">
                                                <i class="fa-solid fa-pen-to-square text-base"></i>
                                            </span>
                                        @endif
                                    @endcan

                                    {{-- Delete --}}
                                    @can('AccessManagement-Delete')
                                        @if ($role->name !== 'Super Admin')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                id="delete-role-{{ $role->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Delete Role"
                                                    onclick="confirmDeleteRole({{ $role->id }}, '{{ addslashes($role->name) }}')"
                                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-trash text-base"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Super Admin cannot delete --}}
                                            <span class="p-2 text-gray-200 cursor-not-allowed" title="Cannot delete System Role">
                                                <i class="fa-solid fa-trash text-base"></i>
                                            </span>
                                        @endif
                                    @endcan

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
        function confirmDeleteRole(id, name) {
            Swal.fire({
                title: 'Delete Role?',
                html: `<span class="text-gray-600">Are you sure you want to delete the <strong>${name}</strong> role?<br><span class="text-sm text-red-500 font-medium">All users with this role will lose their access.</span></span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-role-' + id).submit();
                }
            });
        }
    </script>
@endpush