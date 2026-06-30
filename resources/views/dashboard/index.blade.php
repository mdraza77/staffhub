@extends('layouts.main')

@section('title', 'Dashboard | StaffHub')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back! Here's what's happening in your workspace.</p>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        {{-- Total Employees --}}
        <a href="{{ route('employees.index') }}"
            class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-blue-100 transition-all group">
            <div
                class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                <i class="fa-solid fa-users text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Employees</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_employees'] }}</p>
            </div>
        </a>

        {{-- Active Employees --}}
        <a href="{{ route('employees.index') }}?status=active"
            class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-green-100 transition-all group">
            <div
                class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active Employees</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_employees'] }}</p>
            </div>
        </a>

        {{-- Inactive Employees --}}
        <a href="{{ route('employees.index') }}?status=inactive"
            class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-yellow-100 transition-all group">
            <div
                class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center group-hover:bg-yellow-100 transition-colors">
                <i class="fa-solid fa-circle-pause text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Inactive Employees</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['inactive_employees'] }}</p>
            </div>
        </a>

        {{-- Departments --}}
        <a href="{{ route('departments.index') }}"
            class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-purple-100 transition-all group">
            <div
                class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                <i class="fa-solid fa-building text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Departments</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_departments'] }}</p>
            </div>
        </a>

    </div>

    {{-- ===== QUICK ACTIONS + RECENT EMPLOYEES ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Employees Table (2/3 width) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Recently Added Employees</h2>
                <a href="{{ route('employees.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View
                    All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                            <th class="px-6 py-3 text-left font-medium">Employee</th>
                            <th class="px-6 py-3 text-left font-medium">Department</th>
                            <th class="px-6 py-3 text-left font-medium">Status</th>
                            <th class="px-6 py-3 text-left font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($recentEmployees as $emp)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        {{-- Avatar: profile pic ya initials --}}
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
                                <td class="px-6 py-3 text-gray-600">
                                    {{ $emp->department->name ?? '—' }}
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
                                <td class="px-6 py-3">
                                    <a href="{{ route('employees.edit', $emp->id) }}"
                                        class="text-blue-500 hover:text-blue-700 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                    <i class="fa-solid fa-users-slash text-2xl mb-2 block"></i>
                                    No employees found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Navigation Panel (1/3 width) --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Quick Navigation</h2>
            </div>
            <div class="p-4 flex flex-col gap-2">

                <a href="{{ route('employees.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                    <i
                        class="fa-solid fa-users w-5 text-center text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                    <span class="text-sm font-medium">All Employees</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-blue-400"></i>
                </a>

                <a href="{{ route('employees.create') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                    <i
                        class="fa-solid fa-user-plus w-5 text-center text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                    <span class="text-sm font-medium">Add Employee</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-blue-400"></i>
                </a>

                <a href="{{ route('departments.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-700 transition-all group">
                    <i
                        class="fa-solid fa-building w-5 text-center text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                    <span class="text-sm font-medium">Departments</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-purple-400"></i>
                </a>

                <a href="{{ route('attendance.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-50 text-gray-700 hover:text-green-700 transition-all group">
                    <i
                        class="fa-solid fa-calendar-check w-5 text-center text-gray-400 group-hover:text-green-600 transition-colors"></i>
                    <span class="text-sm font-medium">Attendance</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-green-400"></i>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-orange-50 text-gray-700 hover:text-orange-700 transition-all group">
                    <i
                        class="fa-solid fa-list-check w-5 text-center text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                    <span class="text-sm font-medium">Tasks</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-orange-400"></i>
                </a>

                <a href="{{ route('roles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 transition-all group">
                    <i
                        class="fa-solid fa-shield-halved w-5 text-center text-gray-400 group-hover:text-indigo-600 transition-colors"></i>
                    <span class="text-sm font-medium">Roles & Permissions</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-indigo-400"></i>
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition-all group">
                    <i
                        class="fa-solid fa-gear w-5 text-center text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    <span class="text-sm font-medium">Settings</span>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-gray-400"></i>
                </a>

            </div>
        </div>

    </div>

@endsection
