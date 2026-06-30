@extends('layouts.main')

@section('title', 'Edit Role | StaffHub')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Role</h1>
            <p class="text-sm text-gray-500 mt-1">Update permissions for <strong>{{ $role->name }}</strong></p>
        </div>
        <a href="{{ route('roles.index') }}"
            class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium text-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Roles
        </a>
    </div>

    {{-- Error Flash --}}
    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== LEFT: Role Info Card ===== --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">

                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Role Details
                    </h3>

                    {{-- Role Name --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}"
                            placeholder="e.g. HR Manager"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror"
                            {{ $role->name === 'Super Admin' ? 'readonly' : '' }}>
                        @error('name')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                        @if ($role->name === 'Super Admin')
                            <p class="text-xs text-purple-500 mt-1">
                                <i class="fa-solid fa-lock mr-1"></i> System role name cannot be changed.
                            </p>
                        @endif
                    </div>

                    {{-- Permission Summary --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-5">
                        <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide mb-1">Selected</p>
                        <p class="text-2xl font-bold text-blue-700" id="selected-count">0</p>
                        <p class="text-xs text-blue-500 mt-0.5">permissions selected</p>
                    </div>

                    {{-- Select / Deselect All --}}
                    <div class="flex gap-2 mb-5">
                        <button type="button" onclick="selectAll()"
                            class="flex-1 text-xs font-semibold px-3 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 transition-colors">
                            <i class="fa-solid fa-check-double mr-1"></i> Select All
                        </button>
                        <button type="button" onclick="deselectAll()"
                            class="flex-1 text-xs font-semibold px-3 py-2 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 transition-colors">
                            <i class="fa-solid fa-xmark mr-1"></i> Clear All
                        </button>
                    </div>

                    {{-- Submit --}}
                    @can('AccessManagement-Edit')
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Update Role
                        </button>
                    @endcan

                    <a href="{{ route('roles.index') }}"
                        class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        Cancel
                    </a>

                </div>
            </div>

            {{-- ===== RIGHT: Permissions Grid ===== --}}
            <div class="lg:col-span-2 flex flex-col gap-4">

                {{-- Validation Error --}}
                @error('permission')
                    <div
                        class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </div>
                @enderror

                @forelse ($permissions as $module => $perms)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                        {{-- Module Header --}}
                        <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                                    <i class="fa-solid fa-layer-group text-indigo-500 text-xs"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $module }}</span>
                                <span class="text-xs text-gray-400 font-normal">({{ $perms->count() }})</span>
                            </div>
                            <button type="button" onclick="toggleModule('{{ $module }}')"
                                class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                                id="toggle-btn-{{ Str::slug($module) }}">
                                Select All
                            </button>
                        </div>

                        {{-- Permission Checkboxes --}}
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3" id="module-{{ Str::slug($module) }}">
                            @foreach ($perms as $perm)
                                <label
                                    class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all group
                                    {{ in_array($perm->name, old('permission', $rolePermissions)) ? 'border-blue-300 bg-blue-50' : 'border-gray-100 hover:border-blue-200 hover:bg-blue-50' }}
                                    has-[:checked]:border-blue-300 has-[:checked]:bg-blue-50">
                                    <input type="checkbox" name="permission[]" value="{{ $perm->name }}"
                                        class="perm-checkbox w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer"
                                        data-module="{{ Str::slug($module) }}"
                                        {{ in_array($perm->name, old('permission', $rolePermissions)) ? 'checked' : '' }}>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-700 group-hover:text-blue-700 transition-colors">
                                            {{ explode('-', $perm->name)[1] ?? $perm->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $perm->name }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-key text-3xl mb-3 block"></i>
                        No permissions found.
                    </div>
                @endforelse

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function updateCount() {
            const total = document.querySelectorAll('.perm-checkbox:checked').length;
            document.getElementById('selected-count').textContent = total;
        }

        function selectAll() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
            updateAllToggleBtns();
            updateCount();
        }

        function deselectAll() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
            updateAllToggleBtns();
            updateCount();
        }

        function toggleModule(module) {
            const slug = module.toLowerCase().replace(/\s+/g, '-');
            const checkboxes = document.querySelectorAll(`[data-module="${slug}"]`);
            const allChecked = [...checkboxes].every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            updateToggleBtn(slug, !allChecked);
            updateCount();
        }

        function updateToggleBtn(slug, allChecked) {
            const btn = document.getElementById('toggle-btn-' + slug);
            if (!btn) return;
            btn.textContent = allChecked ? 'Deselect All' : 'Select All';
        }

        function updateAllToggleBtns() {
            document.querySelectorAll('[id^="toggle-btn-"]').forEach(btn => {
                const slug = btn.id.replace('toggle-btn-', '');
                const checkboxes = document.querySelectorAll(`[data-module="${slug}"]`);
                const allChecked = [...checkboxes].every(cb => cb.checked);
                btn.textContent = allChecked ? 'Deselect All' : 'Select All';
            });
        }

        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const slug = this.dataset.module;
                const moduleCheckboxes = document.querySelectorAll(`[data-module="${slug}"]`);
                const allChecked = [...moduleCheckboxes].every(c => c.checked);
                updateToggleBtn(slug, allChecked);
                updateCount();
            });
        });

        // Init — existing permissions already checked hain
        updateCount();
        updateAllToggleBtns();
    </script>
@endpush
