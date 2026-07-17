@extends('layouts.main')

@section('title', 'New Project | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New Project</h1>
            <p class="text-sm text-gray-500 mt-1">Set up project timeline, description and initialization parameters.</p>
        </div>
        <div>
            @can('Project-Index')
                <x-back-button :url="route('projects.index')" label="Back to Projects" />
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl">
        <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. StaffHub HRMS, Aman Steel ERP"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all bg-white" required>
                        <option value="planning" {{ old('status') === 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="in_progress" {{ old('status', 'in_progress') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Empty space or other field -->
                <div></div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Project Description</label>
                    <textarea name="description" rows="5" placeholder="Summarize project requirements, clients and deliverables..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('projects.index') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">Cancel</a>
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold text-sm shadow-sm">Create Project</button>
            </div>
        </form>
    </div>
@endsection
