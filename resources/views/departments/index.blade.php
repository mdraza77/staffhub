@extends('layouts.main')

@section('title', 'Departments | StaffHub')

@section('content')
    <div class="mb-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Departments</h1>
                <nav class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Departments</span>
                </nav>
            </div>
            @can('Department-Create')
                <a href="{{ route('departments.create') }}">
                    <button type="button"
                        class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                        <i class="fa-solid fa-plus text-lg"></i> Create Department
                    </button>
                </a>
            @endcan
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3 animate-fade-in">
                <i class="fa-solid fa-circle-check text-green-600 text-lg mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg animate-fade-in">
                <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Departments Table Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="departments" class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Department Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Created On</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($departments as $key => $department)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $department->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $department->description ?? '---' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $department->created_at->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($department->deleted_at)
                                        <span
                                            class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">
                                            Deleted
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($department->deleted_at === null)
                                            @can('Department-Edit')
                                                <a href="{{ route('departments.edit', $department->id) }}"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                                </a>
                                            @endcan
                                            @can('Department-Delete')
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Delete" onclick="confirmDelete({{ $department->id }})">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            @endcan
                                        @else
                                            @can('Department-Restore')
                                                <button
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Restore" onclick="confirmRestore({{ $department->id }})">
                                                    <i class="fa-solid fa-rotate-left text-lg"></i>
                                                </button>
                                            @endcan
                                            @can('Department-ForceDelete')
                                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Permanent Delete"
                                                    onclick="confirmPermanentDelete({{ $department->id }})">
                                                    <i class="fa-solid fa-trash-can text-lg"></i>
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        {{-- @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 font-medium mb-2">No departments found</p>
                                        <p class="text-gray-400 text-sm">Click "Create Department" to add your first
                                            department</p>
                                    </div>
                                </td>
                            </tr> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            {{ $departments->links() }}
        </div>
    </div>
@push('scripts')
    <script>
        function confirmDelete(departmentId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You can revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + departmentId).submit();
                }
            });
        }

        function confirmRestore(departmentId) {
            Swal.fire({
                title: "Restore Department?",
                text: "This department will be restored to active status.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#10b981",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-form-' + departmentId).submit();
                }
            });
        }

        function confirmPermanentDelete(departmentId) {
            Swal.fire({
                title: "Permanent Delete?",
                text: "This cannot be undone! The department will be permanently deleted.",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete permanently!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('permanent-delete-form-' + departmentId).submit();
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#departments').DataTable({
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

    <!-- Hidden delete forms for each department -->
    @foreach ($departments as $department)
        <form id="delete-form-{{ $department->id }}" action="{{ route('departments.destroy', $department->id) }}"
            method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @if ($department->deleted_at !== null)
            <form id="restore-form-{{ $department->id }}" action="{{ route('departments.restore', $department->id) }}"
                method="POST" class="hidden">
                @csrf
            </form>
            <form id="permanent-delete-form-{{ $department->id }}"
                action="{{ route('departments.force-delete', $department->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach
@endsection
