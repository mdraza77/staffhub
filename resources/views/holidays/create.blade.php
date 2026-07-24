@extends('layouts.main')

@section('title', 'Add Holiday | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Holiday</h1>
            <p class="text-sm text-gray-500 mt-1">Define a new holiday for the company calendar</p>
        </div>
        <x-back-button :url="route('holidays.index')" label="Back to Holidays" />
    </div>

    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('holidays.store') }}" method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-3xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Holiday Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror"
                    placeholder="e.g. Independence Day" required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span
                        class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('start_date') border-red-500 @enderror"
                    required>
                @error('start_date')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span
                        class="text-gray-400 text-xs">(Optional)</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('end_date') border-red-500 @enderror">
                @error('end_date')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Holiday Type <span
                        class="text-red-500">*</span></label>
                <select name="type"
                    class="type w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('type') border-red-500 @enderror"
                    required>
                    <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Public Holiday</option>
                    <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Optional Holiday</option>
                    <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company Holiday</option>
                </select>
                @error('type')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                        class="text-red-500">*</span></label>
                <select name="status"
                    class="status w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('status') border-red-500 @enderror"
                    required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 @error('description') border-red-500 @enderror"
                    placeholder="Brief details about the holiday...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
            {{-- <a href="{{ route('holidays.index') }}"
                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                Cancel
            </a> --}}
            <button type="submit"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium text-sm shadow-sm">
                Save Holiday
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.type').select2();
                $('.status').select2();
            });
        </script>
    @endpush
@endsection