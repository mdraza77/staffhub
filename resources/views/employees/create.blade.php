@extends('layouts.main')

@section('title', 'Add Employee | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Employee</h1>
            <p class="text-sm text-gray-500 mt-1">Fill in the details to onboard a new staff member</p>
        </div>
        <x-back-button :url="route('employees.index')" label="Back to Employees" />
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                    <option value="">Select Department</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
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

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Personal Details</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="flex gap-2">
                    <select name="phone_country_code"
                        class="w-28 border border-gray-300 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white dark:bg-zinc-950 dark:text-zinc-100 @error('phone_country_code') border-red-500 @enderror">
                        @foreach ($countryCodes as $code => $label)
                            <option value="{{ $code }}" {{ old('phone_country_code', '+91') == $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 9876543210"
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('phone') border-red-500 @enderror">
                </div>
                @error('phone_country_code')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
                @error('phone')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact Number</label>
                <div class="flex gap-2">
                    <select name="emergency_country_code"
                        class="w-28 border border-gray-300 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white dark:bg-zinc-950 dark:text-zinc-100 @error('emergency_country_code') border-red-500 @enderror">
                        @foreach ($countryCodes as $code => $label)
                            <option value="{{ $code }}" {{ old('emergency_country_code', '+91') == $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                        placeholder="e.g. 9876543210"
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('emergency_contact') border-red-500 @enderror">
                </div>
                @error('emergency_country_code')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
                @error('emergency_contact')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <select name="gender"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('gender') border-red-500 @enderror">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('date_of_birth') border-red-500 @enderror">
                @error('date_of_birth')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Group</label>
                <select name="blood_group"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('blood_group') border-red-500 @enderror">
                    <option value="">Select Blood Group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                @error('blood_group')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="3" placeholder="Enter complete residential address..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-2 border-b border-gray-100 pb-2 mb-2 mt-4">
                <h3 class="text-lg font-semibold text-gray-800">Documents & Uploads</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>

                <div
                    class="flex items-center gap-4 mb-3 p-3 bg-gray-50 dark:bg-zinc-950/40 rounded-lg border border-gray-200 dark:border-zinc-800">
                    <img id="profile-preview" src="{{ asset('img/dummy-user-pic.png') }}" alt="Profile Preview"
                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-zinc-800">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Profile Photo</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 font-normal">Upload an image below</p>
                    </div>
                </div>

                <input type="file" name="profile" id="profile-input" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPG, PNG, GIF.</p>
                @error('profile')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Signature Image</label>

                <div
                    class="flex items-center gap-4 mb-3 p-3 bg-gray-50 dark:bg-zinc-950/40 rounded-lg border border-gray-200 dark:border-zinc-800">
                    <img id="signature-preview" src="{{ asset('img/dummy-signature.png') }}" alt="Signature Preview"
                        class="w-16 h-16 object-contain border-2 border-gray-200 dark:border-zinc-800 bg-white dark:bg-transparent mix-blend-multiply dark:mix-blend-screen"
                        draggable="false">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-zinc-300">Signature</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 font-normal">Upload an image below</p>
                    </div>
                </div>

                <input type="file" name="signature" id="signature-input" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Allowed formats: JPG, PNG, GIF.</p>
                @error('signature')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            {{-- <a href="{{ route('employees.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Cancel</a>
            --}}
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
            const emergencyInput = document.querySelector('input[name="emergency_contact"]');

            setupPhoneValidation(phoneInput);
            setupPhoneValidation(emergencyInput);

            // Live preview for profile image
            const profileInput = document.getElementById('profile-input');
            const profilePreview = document.getElementById('profile-preview');
            if (profileInput && profilePreview) {
                profileInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        profilePreview.src = URL.createObjectURL(file);
                    }
                });
            }

            // Live preview for signature image
            const signatureInput = document.getElementById('signature-input');
            const signaturePreview = document.getElementById('signature-preview');
            if (signatureInput && signaturePreview) {
                signatureInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        signaturePreview.src = URL.createObjectURL(file);
                    }
                });
            }
        });
    </script>
@endpush