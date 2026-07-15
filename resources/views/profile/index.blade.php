@extends('layouts.main')

@section('title', 'My Profile | StaffHub')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your personal account information</p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
            Back to Dashboard
        </a>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== LEFT: Profile Photo Card ===== --}}
            <div class="lg:col-span-1 flex flex-col gap-5">

                {{-- Photo Card --}}
                <div
                    class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">

                    {{-- Avatar --}}
                    <div class="relative mb-4 group">
                        @if ($user->profile)
                            <img src="{{ $user->profile }}" alt="{{ $user->name }}" id="profile-preview"
                                class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow">
                        @else
                            <div id="profile-initials"
                                class="w-28 h-28 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-3xl border-4 border-blue-50 shadow">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <img id="profile-preview"
                                class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow hidden" src=""
                                alt="Preview">
                        @endif

                        {{-- Overlay on hover --}}
                        <label for="profile-input"
                            class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="fa-solid fa-camera text-white text-xl"></i>
                        </label>
                    </div>

                    <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $user->designation ?? 'No Designation' }}</p>

                    {{-- Role Badge --}}
                    @if ($user->roles->isNotEmpty())
                        <span
                            class="mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-200">
                            <i class="fa-solid fa-shield-halved mr-1 text-[10px]"></i>
                            {{ $user->roles->first()->name }}
                        </span>
                    @endif

                    {{-- Hidden file input --}}
                    @can('Employee-Profile-Edit')
                        <input type="file" name="profile" id="profile-input" accept="image/*" class="hidden"
                            onchange="previewImage(this)">

                        <div class="flex items-center justify-between gap-3 inline-flex">
                            <div>
                                <button type="button" onclick="document.getElementById('profile-input').click()"
                                    class="mt-4 w-full px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i class="fa-solid fa-upload mr-1"></i> Change Photo
                                </button>
                            </div>

                            {{-- Remove photo button --}}
                            <div class="mt-4">
                                @if ($user->profile)
                                    <form action="{{ route('profile.remove-photo') }}" method="POST" class="w-full mt-2"
                                        id="remove-photo-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmRemovePhoto()"
                                            class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                            <i class="fa-solid fa-trash mr-1"></i> Remove Photo
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endcan

                    <p class="text-xs text-gray-400 mt-3">JPG, PNG, GIF — Max 2MB</p>
                    @error('profile')
                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Account Info Card --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Account Info</h3>
                    <ul class="flex flex-col gap-3">
                        <li class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-id-badge text-blue-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Employee ID</p>
                                <p class="font-medium text-gray-700">{{ $user->employee_id ?? '—' }}</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-building text-purple-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Department</p>
                                <p class="font-medium text-gray-700">{{ $user->department->name ?? '—' }}</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-calendar text-green-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Joined</p>
                                <p class="font-medium text-gray-700">
                                    {{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('d M, Y') : '—' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Member Since</p>
                                <p class="font-medium text-gray-700">
                                    {{ $user->created_at->format('d M, Y') }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- ===== RIGHT: Edit Forms ===== --}}
            <div class="lg:col-span-2 flex flex-col gap-5">

                {{-- Personal Information --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-user text-blue-500 text-sm"></i>
                        <h3 class="text-base font-semibold text-gray-800">Personal Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('phone') border-red-500 @enderror"
                                placeholder="+91 00000 00000">
                            @error('phone')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Designation --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <input type="text" name="designation" value="{{ old('designation', $user->designation) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('designation') border-red-500 @enderror"
                                placeholder="e.g. Software Engineer">
                            @error('designation')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Change Password --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-orange-500 text-sm"></i>
                        <h3 class="text-base font-semibold text-gray-800">Change Password</h3>
                        <span class="text-xs text-gray-400 font-normal ml-1">(leave blank to keep current)</span>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- New Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="new-password"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror"
                                    placeholder="Min. 8 characters">
                                <button type="button" onclick="togglePassword('new-password', 'eye1')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye text-sm" id="eye1"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm-password"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                    placeholder="Re-enter new password">
                                <button type="button" onclick="togglePassword('confirm-password', 'eye2')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-eye text-sm" id="eye2"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Password strength hint --}}
                        <div class="sm:col-span-2">
                            <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-xs text-blue-600">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                Password must be at least <strong>8 characters</strong>. Leave blank if you don't want to
                                change it.
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Read-only Info --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-purple-500 text-sm"></i>
                        <h3 class="text-base font-semibold text-gray-800">Employment Details</h3>
                        <span class="text-xs text-gray-400 font-normal ml-1">(managed by admin)</span>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Employee ID</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fa-solid fa-id-badge text-gray-400 text-xs"></i>
                                <p class="text-sm text-gray-600">{{ $user->employee_id ?? '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Department</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fa-solid fa-building text-gray-400 text-xs"></i>
                                <p class="text-sm text-gray-600">{{ $user->department->name ?? '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Joining Date</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fa-solid fa-calendar text-gray-400 text-xs"></i>
                                <p class="text-sm text-gray-600">
                                    {{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('d M, Y') : '—' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                @if ($user->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Active
                                    </span>
                                @elseif ($user->status === 'inactive')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Inactive
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>Terminated
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Assigned Role</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fa-solid fa-shield-halved text-gray-400 text-xs"></i>
                                @if ($user->roles->isNotEmpty())
                                    <span class="text-sm font-medium text-indigo-600">
                                        {{ $user->roles->first()->name }}
                                    </span>
                                @else
                                    <p class="text-sm text-gray-400">No role assigned</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Member Since</p>
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                <i class="fa-solid fa-clock text-gray-400 text-xs"></i>
                                <p class="text-sm text-gray-600">{{ $user->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Cancel
                    </a>
                    @can('Employee-Profile-Edit')
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors shadow-sm">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Save Changes
                        </button>
                    @endcan
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // ===== IMAGE PREVIEW =====
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Initials div hide karo
                    const initials = document.getElementById('profile-initials');
                    if (initials) initials.classList.add('hidden');

                    // Preview dikhao
                    const preview = document.getElementById('profile-preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ===== PASSWORD TOGGLE =====
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ===== REMOVE PHOTO CONFIRM =====
        function confirmRemovePhoto() {
            Swal.fire({
                title: 'Remove Profile Photo?',
                html: `<span class="text-gray-600">Your profile picture will be removed and replaced with initials.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Yes, Remove',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('remove-photo-form').submit();
                }
            });
        }
    </script>
@endpush