@extends('layouts.main')

@section('title', 'Create Department | StaffHub')

@section('content')
    <div class="mb-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-4xl font-bold text-gray-900">Create Department</h1>
                </div>
                <nav class="flex items-center gap-2 text-sm text-gray-600">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('departments.index') }}" class="hover:text-blue-600 transition-colors">Departments</a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Create</span>
                </nav>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg animate-fade-in">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-600 text-lg mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-red-800 font-semibold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Department Form -->
        <div class="max-w-1xl">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-plus text-lg"></i>
                        New Department Details
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Fill in the information below to create a new department</p>
                </div>

                <!-- Form Content -->
                <form action="{{ route('departments.store') }}" method="POST" class="p-8">
                    @csrf

                    <!-- Department Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fa-solid fa-building text-blue-600 mr-2"></i>
                            Department Name
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            class="w-full px-4 py-4 border {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-50' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-50' }} rounded-lg focus:outline-none focus:ring-2 transition-colors"
                            id="name" name="name" value="{{ old('name') }}" required
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
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-50 transition-colors resize-none"
                            id="description" name="description" rows="5"
                            placeholder="Provide a brief description of this department, its responsibilities, and scope...">{{ old('description') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Optional - helps team members understand the department's role
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('departments.index') }}"
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium flex items-center gap-2">
                            <i class="fa-solid fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2 ml-auto">
                            <i class="fa-solid fa-check"></i>
                            Create Department
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 text-lg mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-blue-900 font-medium text-sm">Department names should be unique and descriptive</p>
                    <p class="text-blue-800 text-xs mt-1">Once created, you can assign employees to this department and
                        configure its settings.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
