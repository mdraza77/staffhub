@extends('layouts.main')

@section('title', 'Edit Department | StaffHub')

@section('content')
    <div class="mb-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-4xl font-bold text-gray-900">Edit Department</h1>
                </div>
                <nav class="flex items-center gap-2 text-sm text-gray-600">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('departments.index') }}" class="hover:text-blue-600 transition-colors">Departments</a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Edit</span>
                </nav>
            </div>
        </div>

        <!-- Edit Department Form -->
        <div class="max-w-1xl">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                <!-- Form Content -->
                <form action="{{ route('departments.update', $department->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Department Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fa-solid fa-building text-blue-600 mr-2"></i>
                            Department Name (Must be Unique)
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                            id="name" name="name" value="{{ old('name', $department->name) }}" required
                            placeholder="e.g. Information Technology, Human Resources, Marketing">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fa-solid fa-align-left text-blue-600 mr-2"></i>
                            Description
                        </label>
                        <textarea
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            id="description" name="description" rows="3"
                            placeholder="Provide a brief description of this department, its responsibilities, and scope...">{{ old('description', $department->description) }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Optional - helps team members understand the department's role
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                        @can('Department-Edit')
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2 ml-auto">
                                Save Changes
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
