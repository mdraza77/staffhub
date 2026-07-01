@extends('layouts.main')

@section('title', 'Dashboard | StaffHub')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">
                Welcome back, <span class="font-semibold text-blue-600">{{ auth()->user()->name }}</span>!
                Here's what's happening in your workspace.
            </p>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-sm font-medium text-gray-700">{{ now()->format('l') }}</p>
            <p class="text-xs text-gray-400">{{ now()->format('d M, Y') }}</p>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Employees --}}
        @can('Employee-Index')
            <a href="{{ route('employees.index') }}"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-blue-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                    <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_employees'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">registered staff</p>
                </div>
            </a>
        @endcan

        {{-- Active Employees --}}
        @can('Employee-Index')
            <a href="{{ route('employees.index') }}?status=active"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-green-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                    <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Active Employees</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_employees'] }}</p>
                    <p class="text-xs text-green-500 mt-0.5">currently working</p>
                </div>
            </a>
        @endcan

        {{-- Inactive Employees --}}
        @can('Employee-Index')
            <a href="{{ route('employees.index') }}?status=inactive"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-yellow-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center group-hover:bg-yellow-100 transition-colors">
                    <i class="fa-solid fa-circle-pause text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inactive Employees</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive_employees'] }}</p>
                    <p class="text-xs text-yellow-500 mt-0.5">not active</p>
                </div>
            </a>
        @endcan

        {{-- Departments --}}
        @can('Department-Index')
            <a href="{{ route('departments.index') }}"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-purple-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                    <i class="fa-solid fa-building text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Departments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_departments'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">total teams</p>
                </div>
            </a>
        @endcan

        {{-- Pending Leaves --}}
        @can('Leave-ApproveReject')
            <a href="{{ route('leaves.index') }}"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-orange-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                    <i class="fa-solid fa-clock text-orange-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pending Leaves</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_leaves'] ?? 0 }}</p>
                    <p class="text-xs text-orange-500 mt-0.5">awaiting approval</p>
                </div>
            </a>
        @endcan

        {{-- Today Attendance --}}
        @can('Attendance-Index')
            <a href="{{ route('attendance.index') }}"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-teal-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center group-hover:bg-teal-100 transition-colors">
                    <i class="fa-solid fa-calendar-check text-teal-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Today's Attendance</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['today_attendance'] ?? 0 }}</p>
                    <p class="text-xs text-teal-500 mt-0.5">present today</p>
                </div>
            </a>
        @endcan

    </div>

    {{-- ===== MAIN CONTENT GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== RECENT EMPLOYEES (2/3) ===== --}}
        @can('Employee-Index')
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-blue-500 text-sm"></i>
                        <h2 class="text-base font-semibold text-gray-800">Recently Added Employees</h2>
                    </div>
                    <a href="{{ route('employees.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View
                        All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="px-6 py-3 text-left font-medium">Employee</th>
                                <th class="px-6 py-3 text-left font-medium">Department</th>
                                <th class="px-6 py-3 text-left font-medium">Role</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                @can('Employee-Edit')
                                    <th class="px-6 py-3 text-left font-medium">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($recentEmployees as $emp)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($emp->profile)
                                                <img src="{{ asset('storage/' . $emp->profile) }}"
                                                    class="w-9 h-9 rounded-full object-cover border border-gray-200"
                                                    alt="{{ $emp->name }}">
                                            @else
                                                <div
                                                    class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm">
                                                    {{ strtoupper(substr($emp->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $emp->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $emp->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600 text-xs">
                                        {{ $emp->department->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        @if ($emp->roles->isNotEmpty())
                                            <span
                                                class="px-2 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-600">
                                                {{ $emp->roles->first()->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        @if ($emp->status === 'active')
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">Active</span>
                                        @elseif ($emp->status === 'inactive')
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">Inactive</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Terminated</span>
                                        @endif
                                    </td>
                                    @can('Employee-Edit')
                                        <td class="px-6 py-3">
                                            <a href="{{ route('employees.edit', $emp->id) }}"
                                                class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors inline-block">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </a>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                                        <i class="fa-solid fa-users-slash text-2xl mb-2 block"></i>
                                        No employees found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endcan

        {{-- ===== RIGHT PANEL ===== --}}
        <div class="flex flex-col gap-5">

            {{-- Quick Navigation --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500 text-sm"></i>
                    <h2 class="text-base font-semibold text-gray-800">Quick Navigation</h2>
                </div>
                <div class="p-3 flex flex-col gap-1">

                    @can('Employee-Index')
                        <a href="{{ route('employees.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-users text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">All Employees</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-blue-400"></i>
                        </a>
                    @endcan

                    @can('Employee-Create')
                        <a href="{{ route('employees.create') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-user-plus text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Add Employee</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-blue-400"></i>
                        </a>
                    @endcan

                    @can('Department-Index')
                        <a href="{{ route('departments.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-building text-purple-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Departments</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-purple-400"></i>
                        </a>
                    @endcan

                    @can('Attendance-Index')
                        <a href="{{ route('attendance.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-teal-50 group-hover:bg-teal-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-calendar-check text-teal-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Attendance</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-teal-400"></i>
                        </a>
                    @endcan

                    @can('Leave-Index')
                        <a href="{{ route('leaves.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-orange-50 text-gray-700 hover:text-orange-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-orange-50 group-hover:bg-orange-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-person-walking-arrow-right text-orange-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Leaves</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-orange-400"></i>
                        </a>
                    @endcan

                    @can('Task-Index')
                        <a href="{{ route('tasks.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-amber-50 text-gray-700 hover:text-amber-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-list-check text-amber-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Tasks</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-amber-400"></i>
                        </a>
                    @endcan

                    @can('AccessManagement-Index')
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-shield-halved text-indigo-500 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Roles & Permissions</span>
                            <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-indigo-400"></i>
                        </a>
                    @endcan

                </div>
            </div>

            {{-- Logged In User Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fa-solid fa-circle-user text-blue-400 text-sm"></i>
                    <h2 class="text-base font-semibold text-gray-800">Logged In As</h2>
                </div>
                <div class="flex items-center gap-3">
                    @if (auth()->user()->profile)
                        <img src="{{ asset('storage/' . auth()->user()->profile) }}"
                            class="w-12 h-12 rounded-full object-cover border-2 border-blue-100"
                            alt="{{ auth()->user()->name }}">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg border-2 border-blue-50">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->roles->isNotEmpty())
                            <span
                                class="mt-1 inline-block px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-600">
                                {{ auth()->user()->roles->first()->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
