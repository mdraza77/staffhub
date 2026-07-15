@extends('layouts.main')

@section('title', 'Edit Employee | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Employee</h1>
            <p class="text-sm text-gray-500 mt-1">Update the details for {{ $employee->name }}</p>
        </div>
        <a href="{{ route('employees.index') }}"
            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-all flex items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>



    <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ===================== ACCOUNT INFORMATION ===================== --}}
            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2">
                <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $employee->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password optional rehega edit mein --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                    <span class="text-gray-400 font-normal text-xs">(leave blank to keep current)</span>
                </label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror">
                @error('password')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
            </div>

            {{-- ===================== EMPLOYMENT DETAILS ===================== --}}
            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Employment Details</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}"
                    class="w-full cursor-not-allowed border border-gray-300 rounded-lg px-4 py-2 outline-none transition-all placeholder-gray-400 bg-gray-50 font-mono text-sm @error('employee_id') border-red-500 @enderror"
                    placeholder="e.g. WP-001" readonly>
                @error('employee_id')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                    placeholder="e.g., +918544568958"
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
                        <option value="{{ $dept->id }}"
                            {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                <input type="text" name="designation" value="{{ old('designation', $employee->designation) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 @error('designation') border-red-500 @enderror"
                    placeholder="e.g. Software Engineer">
                @error('designation')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Joining Date</label>
                <input type="date" name="joining_date"
                    value="{{ old('joining_date', $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('Y-m-d') : '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('joining_date') border-red-500 @enderror">
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
                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active
                    </option>
                    <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>
                        Inactive</option>
                    <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>
                        Terminated</option>
                </select>
                @error('status')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- ===================== ASSIGN ROLE ===================== --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Role</label>
                <select name="role"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('role') border-red-500 @enderror">
                    <option value="">— No Role —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ old('role', $employeeRole) == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- ===================== PROFILE PICTURE ===================== --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>

                {{-- Current profile preview --}}
                @if ($employee->profile)
                    <div
                        class="flex items-center gap-4 mb-3 p-3 bg-gray-50 dark:bg-zinc-950/40 rounded-lg border border-gray-200 dark:border-zinc-800">
                        <img src="{{ $employee->profile }}" alt="Current Profile"
                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-zinc-800">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Current Photo</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 font-normal">Upload a new image below to
                                replace it</p>
                        </div>
                    </div>
                @endif

                <input type="file" name="profile" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPG, PNG, GIF.</p>
                @error('profile')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- ===================== SIGNATURE ===================== --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Signature Image</label>

                {{-- Current signature preview --}}
                @if ($employee->signature)
                    <div
                        class="flex items-center gap-4 mb-3 p-3 bg-gray-50 dark:bg-zinc-950/40 rounded-lg border border-gray-200 dark:border-zinc-800">
                        <img src="{{ $employee->signature }}" alt="Current Signature"
                            class="w-16 h-16 object-contain border-2 border-gray-200 dark:border-zinc-800 bg-white dark:bg-transparent mix-blend-multiply dark:mix-blend-screen dark:invert"
                            draggable="false">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Current Signature</p>
                            <p class="text-xs text-gray-500 dark:text-zinc-400 font-normal">Upload a new signature below to
                                replace it</p>
                        </div>
                    </div>
                @endif

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
            @can('Employee-Edit')
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">Update
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

            // Validation function ko alag kar diya
            const validatePhone = () => {
                const val = phoneInput.value.trim();

                if (val === '') {
                    phoneInput.classList.remove('border-red-500');
                    warnSpan.classList.add('hidden');
                    phoneInput.setCustomValidity('');
                    return;
                }

                // Strict Regex: Starts with '+', then 1-3 digit country code, exactly 10 digits
                const regex = /^\+\d{1,3}\d{10}$/;

                if (!regex.test(val)) {
                    phoneInput.classList.add('border-red-500');
                    warnSpan.classList.remove('hidden');
                    phoneInput.setCustomValidity('Invalid phone format.');
                } else {
                    phoneInput.classList.remove('border-red-500');
                    warnSpan.classList.add('hidden');
                    phoneInput.setCustomValidity('');
                }
            };

            // 1. Jab user type karega tab validate hoga
            phoneInput.addEventListener('input', validatePhone);

            // 2. IMPORTANT FOR EDIT PAGE: Page load hote hi purana data validate check karega
            if (phoneInput.value.trim() !== '') {
                validatePhone();
            }
        });
    </script>
@endpush
