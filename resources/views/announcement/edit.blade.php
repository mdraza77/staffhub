@extends('layouts.main')

@section('title', 'Edit Announcement | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Announcement</h1>
            <p class="text-sm text-gray-500 mt-1">Modify the details of your announcement</p>
        </div>
        <a href="{{ route('announcements.index') }}"
            class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-3xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Announcement Title <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $announcement->title) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('title') border-red-500 @enderror"
                    placeholder="e.g. Scheduled Server Maintenance" required>
                @error('title')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                <input type="date" name="publish_date"
                    value="{{ old('publish_date', $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('Y-m-d') : '') }}"
                    max="{{ now()->format('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('publish_date') border-red-500 @enderror">
                @error('publish_date')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span
                        class="text-red-500">*</span></label>
                <select name="priority"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('priority') border-red-500 @enderror"
                    required>
                    <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Low
                    </option>
                    <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>
                        Medium</option>
                    <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>High
                    </option>
                </select>
                @error('priority')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                        class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('status') border-red-500 @enderror"
                    required>
                    <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft
                    </option>
                    <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>
                        Published</option>
                </select>
                @error('status')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Message <span
                        class="text-red-500">*</span></label>
                <textarea name="message" rows="6"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 @error('message') border-red-500 @enderror"
                    placeholder="Write details of the announcement here..."
                    required>{{ old('message', $announcement->message) }}</textarea>
                @error('message')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
            <a href="{{ route('announcements.index') }}"
                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                Cancel
            </a>
            <button type="submit"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium text-sm shadow-sm">
                Update Announcement
            </button>
        </div>
    </form>
@endsection