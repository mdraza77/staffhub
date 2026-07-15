@extends('layouts.main')

@section('title', 'Add Employee | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Employee</h1>
            <p class="text-sm text-gray-500 mt-1">Fill in the details to onboard a new staff member</p>
        </div>
        <a href="{{ route('employees.index') }}"
            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>



    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2">
                <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                        class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span
                        class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                    required>
            </div>

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Employment Details</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID <span
                        class="text-xs text-gray-400 font-normal">(Auto-generated)</span></label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $nextEmployeeId) }}"
                    class="w-full cursor-not-allowed border border-gray-300 rounded-lg px-4 py-2 outline-none transition-all placeholder-gray-400 bg-gray-50 font-mono text-sm"
                    placeholder="e.g. SH-2607-001" required readonly>
                @error('employee_id')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g., +918544568958"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('phone') border-red-500 @enderror">
                @error('phone')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                    <option value="">Select Department</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                <input type="text" name="designation" value="{{ old('designation') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                    placeholder="e.g. Software Engineer">
                @error('designation')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Joining Date</label>
                <input type="date" name="joining_date" value="{{ old('joining_date', now()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                @error('joining_date')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                        class="text-red-500">*</span></label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white"
                    required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
                @error('status')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Role</label>
                <select name="role"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('role') border-red-500 @enderror">
                    <option value="">— No Role —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                <input type="file" name="profile" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPG, PNG, GIF.</p>
                @error('profile')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Signature Image</label>
                <input type="file" name="signature" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPG, PNG, GIF.</p>
                @error('signature')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('employees.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a>
            @can('Employee-Create')
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">Save
                    Employee</button>
            @endcan
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const phoneInput = document.querySelector('input[name="phone"]');
            if (!phoneInput) return;

            // Dynamic warning message setup
            const warnSpan = document.createElement('span');
            warnSpan.className = 'text-xs text-red-500 mt-1 hidden block';
            warnSpan.textContent = 'Format invalid. Example: +919876543210 (Must include + and country code)';
            phoneInput.parentNode.appendChild(warnSpan);

            phoneInput.addEventListener('input', function() {
                const val = this.value.trim();

                // Clear styling if input is empty
                if (val === '') {
                    this.classList.remove('border-red-500');
                    warnSpan.classList.add('hidden');
                    this.setCustomValidity('');
                    return;
                }

                // Regex Logic: Starts with '+', then 1-3 digit country code, then exactly 10 digits
                const regex = /^\+\d{1,3}\d{10}$/;

                if (!regex.test(val)) {
                    this.classList.add('border-red-500');
                    warnSpan.classList.remove('hidden');
                    this.setCustomValidity('Invalid phone format.');
                } else {
                    this.classList.remove('border-red-500');
                    warnSpan.classList.add('hidden');
                    this.setCustomValidity('');
                }
            });
        });
    </script>
@endpush
