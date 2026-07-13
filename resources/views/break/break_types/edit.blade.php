@extends('layouts.main')

@section('title', 'Edit Break Type | StaffHub')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-2 mb-6">
                <a href="{{ route('break-types.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Break Type</h1>
                    <p class="text-sm text-gray-500">Modify details or configuration settings for the break type.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <form action="{{ route('break-types.update', $breakType->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Break Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $breakType->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                placeholder="e.g. Tea Break, Lunch Break">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1">Duration
                                (Minutes) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_minutes" id="duration_minutes" required min="1"
                                max="1440" value="{{ old('duration_minutes', $breakType->duration_minutes) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('duration_minutes') border-red-500 @enderror">
                            @error('duration_minutes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div>
                            <label for="icon" class="block text-sm font-semibold text-gray-700 mb-1">FontAwesome Icon
                                Class</label>
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $breakType->icon) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('icon') border-red-500 @enderror"
                                placeholder="e.g. fa-solid fa-mug-hot">
                            <span class="text-[10px] text-gray-400 block mt-1">Suggested icons: <code>fa-solid
                                    fa-mug-hot</code> (Tea), <code>fa-solid fa-utensils</code> (Lunch), <code>fa-solid
                                    fa-apple-whole</code> (Snack), <code>fa-solid fa-smoking</code> (Quick Break),
                                <code>fa-solid fa-face-smile</code> (Refresher).</span>
                            @error('icon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select name="is_active" id="is_active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1" {{ old('is_active', $breakType->is_active) ? 'selected' : '' }}>
                                    Active & Available</option>
                                <option value="0" {{ old('is_active', $breakType->is_active) ? '' : 'selected' }}>
                                    Inactive (Hidden)</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                        <a href="{{ route('break-types.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-750 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            Update Break Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
