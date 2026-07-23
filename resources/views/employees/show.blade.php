@extends('layouts.main')

@section('title', 'Employee Details | StaffHub')

@section('content')

    {{-- ===== HEADER ===== --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employee Details</h1>
            <p class="text-sm text-gray-500 mt-1">Full profile information for {{ $employee->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            @can('Employee-Edit')
                @if ($employee->trashed() || $employee->hasRole('Super Admin'))
                @else
                    <a href="{{ route('employees.edit', $employee->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endif
            @endcan
            <x-back-button :url="route('employees.index')" label="Back to Employees" />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT: PROFILE CARD ===== --}}
        <div class="lg:col-span-1 flex flex-col gap-5">

            {{-- Profile Picture + Basic Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">
                @if ($employee->profile)
                    <img src="{{ $employee->profile }}" alt="{{ $employee->name }}"
                        class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow mb-4">
                @else
                    <div
                        class="w-28 h-28 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-3xl mb-4 border-4 border-blue-50 shadow">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                @endif

                <h2 class="text-xl font-bold text-gray-800">{{ $employee->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $employee->designation ?? 'No Designation' }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $employee->email }}</p>

                {{-- Status Badge --}}
                <div class="mt-4">
                    {{-- @if ($employee->status === 'active')
                    <span
                        class="px-4 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                        <i class="fa-solid fa-circle text-green-500 text-[8px] mr-1"></i> Active
                    </span>
                    @elseif ($employee->status === 'inactive')
                    <span
                        class="px-4 py-1.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                        <i class="fa-solid fa-circle text-yellow-500 text-[8px] mr-1"></i> Inactive
                    </span>
                    @else
                    <span
                        class="px-4 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                        <i class="fa-solid fa-circle text-red-500 text-[8px] mr-1"></i> Terminated
                    </span>
                    @endif --}}
                    <x-badge :value="$employee->status" />
                </div>
            </div>

            {{-- Quick Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Quick Info</h3>
                <ul class="flex flex-col gap-3">
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-id-badge text-blue-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Employee ID</p>
                            <p class="font-medium text-gray-700">{{ $employee->employee_id ?? '—' }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-building text-purple-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Department</p>
                            <p class="font-medium text-gray-700">{{ $employee->department->name ?? '—' }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone text-green-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p class="font-medium text-gray-700">{{ $employee->phone ?? '—' }}</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-calendar text-orange-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Joining Date</p>
                            <p class="font-medium text-gray-700">
                                {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') : '—' }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Signature Card --}}
            @if ($employee->signature && $employee->signature !== 'signatures/dummy_signature.png')
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-100 dark:border-zinc-800 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3 uppercase tracking-wide">
                        Signature</h3>
                    <div
                        class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-zinc-950/40 rounded-lg border border-gray-100 dark:border-zinc-800">
                        <img src="{{ str_starts_with($employee->signature, 'http') ? $employee->signature : asset('storage/' . $employee->signature) }}"
                            alt="Signature" class="max-h-16 object-contain mix-blend-multiply dark:mix-blend-screen"
                            draggable="false">
                    </div>
                </div>
            @endif

            {{-- Danger Zone --}}
            @if ($employee->trashed())
                @can('Employee-Restore')
                    <div class="bg-white rounded-xl border border-green-150 shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-green-600 mb-3 uppercase tracking-wide">Restore Employee</h3>
                        <p class="text-xs text-gray-500 mb-4">Restore this employee's profile to active status.</p>
                        <form id="restore-employee-form" action="{{ route('employees.restore', $employee->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmRestore(event)"
                                class="w-full px-4 py-2 bg-green-50 text-green-600 border border-green-200 rounded-lg hover:bg-green-600 hover:text-white transition-all text-sm font-medium">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restore Employee
                            </button>
                        </form>
                    </div>
                @endcan
            @else
                @can('Employee-Delete')
                    @if (!$employee->hasRole('Super Admin'))
                        <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5">
                            <h3 class="text-sm font-semibold text-red-600 mb-3 uppercase tracking-wide">Danger Zone</h3>
                            <p class="text-xs text-gray-500 mb-4">Permanently delete this employee.</p>
                            <form id="delete-employee-form" action="{{ route('employees.destroy', $employee->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event)"
                                    class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-600 hover:text-white transition-all text-sm font-medium">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete Employee
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            @endif

        </div>

        {{-- ===== RIGHT: DETAIL SECTIONS ===== --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Account Information --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-user text-blue-500"></i>
                    <h3 class="text-base font-semibold text-gray-800">Account Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Full Name</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Email Address</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Account Created</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $employee->created_at->format('d M, Y — h:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Last Updated</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $employee->updated_at->format('d M, Y — h:i A') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Employment Details --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-purple-500"></i>
                    <h3 class="text-base font-semibold text-gray-800">Employment Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Employee ID</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->employee_id ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Designation</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->designation ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Department</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->department->name ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Phone Number</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->phone ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Joining Date</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                        {{-- @if ($employee->status === 'active')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">Active</span>
                        @elseif ($employee->status === 'inactive')
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">Inactive</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Terminated</span>
                        @endif --}}
                        <x-badge :value="$employee->status" />
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Assigned Role</p>
                        @if ($employee->roles->isNotEmpty())
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold">
                                <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                {{ $employee->roles->first()->name }}
                            </span>
                        @else
                            <p class="text-sm font-medium text-gray-400">No role assigned</p>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Personal Details --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-address-card text-teal-500"></i>
                    <h3 class="text-base font-semibold text-gray-800">Personal Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Gender</p>
                        <p class="text-sm font-medium text-gray-800 capitalize">{{ $employee->gender ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date of Birth</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M, Y') : '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Blood Group</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->blood_group ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Emergency Contact</p>
                        <p class="text-sm font-medium text-gray-800">{{ $employee->emergency_contact ?? '—' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Residential Address</p>
                        <p class="text-sm font-medium text-gray-850 whitespace-pre-line leading-relaxed">
                            {{ $employee->address ?? '—' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-green-500"></i>
                    <h3 class="text-base font-semibold text-gray-800">Timeline</h3>
                </div>
                <div class="p-6">
                    <ol class="relative border-l-2 border-gray-100 ml-3 flex flex-col gap-6">

                        <li class="ml-6">
                            <span
                                class="absolute -left-[9px] w-4 h-4 rounded-full bg-blue-500 border-2 border-white"></span>
                            <p class="text-xs text-gray-400">Account Created</p>
                            <p class="text-sm font-medium text-gray-700 mt-0.5">
                                {{ $employee->created_at->format('d M, Y') }}
                                <span class="text-gray-400 font-normal">at
                                    {{ $employee->created_at->format('h:i A') }}</span>
                            </p>
                        </li>

                        @if ($employee->joining_date)
                            <li class="ml-6">
                                <span
                                    class="absolute -left-[9px] w-4 h-4 rounded-full bg-green-500 border-2 border-white"></span>
                                <p class="text-xs text-gray-400">Joined Company</p>
                                <p class="text-sm font-medium text-gray-700 mt-0.5">
                                    {{ \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') }}
                                </p>
                            </li>
                        @endif

                        @if ($employee->updated_at != $employee->created_at)
                            <li class="ml-6">
                                <span
                                    class="absolute -left-[9px] w-4 h-4 rounded-full bg-orange-400 border-2 border-white"></span>
                                <p class="text-xs text-gray-400">Last Profile Update</p>
                                <p class="text-sm font-medium text-gray-700 mt-0.5">
                                    {{ $employee->updated_at->format('d M, Y') }}
                                    <span class="text-gray-400 font-normal">at
                                        {{ $employee->updated_at->format('h:i A') }}</span>
                                </p>
                            </li>
                        @endif

                        @if ($employee->status === 'terminated')
                            <li class="ml-6">
                                <span class="absolute -left-[9px] w-4 h-4 rounded-full bg-red-500 border-2 border-white"></span>
                                <p class="text-xs text-gray-400">Employment Terminated</p>
                                <p class="text-sm font-medium text-red-600 mt-0.5">Account marked as terminated</p>
                            </li>
                        @endif

                    </ol>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this employee?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // bg-red-500
                cancelButtonColor: '#4b5563', // bg-gray-600
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-employee-form').submit();
                }
            });
        }

        function confirmRestore(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Restore Employee?',
                text: "Are you sure you want to restore {{ addslashes($employee->name) }} to active status?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // bg-green-500
                cancelButtonColor: '#4b5563', // bg-gray-600
                confirmButtonText: 'Yes, restore employee!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('restore-employee-form').submit();
                }
            });
        }
    </script>
@endpush