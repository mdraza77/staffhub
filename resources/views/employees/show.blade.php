@extends('layouts.main')

@section('title', 'Employee Details | WorkPilot')

@section('content')

    {{-- ===== HEADER ===== --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employee Details</h1>
            <p class="text-sm text-gray-500 mt-1">Full profile information for {{ $employee->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('employees.edit', $employee->id) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
            <a href="{{ route('employees.index') }}"
                class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium text-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT: PROFILE CARD ===== --}}
        <div class="lg:col-span-1 flex flex-col gap-5">

            {{-- Profile Picture + Basic Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">
                @if ($employee->profile)
                    <img src="{{ asset('storage/' . $employee->profile) }}" alt="{{ $employee->name }}"
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
                    @if ($employee->status === 'active')
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
                    @endif
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

            {{-- Danger Zone --}}
            <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-red-600 mb-3 uppercase tracking-wide">Danger Zone</h3>
                <p class="text-xs text-gray-500 mb-4">Permanently delete this employee. This action cannot be undone.</p>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete {{ addslashes($employee->name) }}? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-600 hover:text-white transition-all text-sm font-medium">
                        <i class="fa-solid fa-trash mr-1"></i> Delete Employee
                    </button>
                </form>
            </div>

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
                        @if ($employee->status === 'active')
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">Active</span>
                        @elseif ($employee->status === 'inactive')
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">Inactive</span>
                        @else
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Terminated</span>
                        @endif
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
                                <span
                                    class="absolute -left-[9px] w-4 h-4 rounded-full bg-red-500 border-2 border-white"></span>
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
