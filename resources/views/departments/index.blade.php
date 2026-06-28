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
            <button type="button"
                class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm"
                data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="fa-solid fa-plus text-lg"></i> Create Department
            </button>
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
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Department Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Created On</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($departments as $key => $department)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $department->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $department->description ?? '---' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $department->created_at->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit" data-bs-toggle="modal" data-bs-target="#editDepartmentModal">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete" onclick="confirmDelete()">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 font-medium mb-2">No departments found</p>
                                        <p class="text-gray-400 text-sm">Click "Create Department" to add your first
                                            department</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl">
                <div class="modal-header bg-gradient-to-r from-blue-600 to-blue-700 text-white border-0">
                    <div>
                        <h5 class="modal-title text-lg font-bold" id="addDepartmentModalLabel">
                            <i class="fa-solid fa-plus me-2"></i>Create New Department
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-6">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Department Name
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-50 transition-colors"
                                id="name" name="name" value="{{ old('name') }}" required
                                placeholder="e.g. IT, HR, Marketing">
                        </div>

                        <div class="mb-0">
                            <label for="description"
                                class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                            <textarea
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-50 transition-colors resize-none"
                                id="description" name="description" rows="3" placeholder="Brief details about this department">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-t border-gray-200 p-6 bg-gray-50">
                        <button type="button"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Save
                            Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this department?')) {
                // Add delete functionality
            }
        }
    </script>
@endsection
