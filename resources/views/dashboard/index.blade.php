@extends('layouts.main')

@section('title', 'Dashboard | StaffHub')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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

    {{-- Flash Messages --}}
    {{-- @if (session('success'))
    <div
        class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
    </div>
    @endif --}}

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

        {{-- Employees on Break --}}
        @can('Break-Room-Access')
            <a href="{{ route('break-room.index') }}"
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-indigo-100 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-mug-hot text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Taking Break</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['taking_break'] ?? 0 }}</p>
                    <p class="text-xs text-indigo-500 mt-0.5">currently in lounge</p>
                </div>
            </a>
        @endcan

    </div>

    {{-- ===== MAIN CONTENT GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left & Center Column (2/3) --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- ===== LATEST ANNOUNCEMENTS ===== --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-blue-500 text-base"></i>
                        <h2 class="text-base font-semibold text-gray-800">Latest Announcements</h2>
                    </div>
                    <a href="{{ route('announcements.index') }}"
                        class="text-sm text-blue-600 hover:underline font-medium">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse ($recentAnnouncements as $announcement)
                        <div
                            class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-all relative overflow-hidden pl-5">
                            {{-- Priority Indicator Bar --}}
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1.5 
                                                                                                                                                                        {{ $announcement->priority === 'high' ? 'bg-red-500' : '' }}
                                                                                                                                                                        {{ $announcement->priority === 'medium' ? 'bg-amber-500' : '' }}
                                                                                                                                                                        {{ $announcement->priority === 'low' ? 'bg-blue-500' : '' }}
                                                                                                                                                                    ">
                            </div>

                            <div class="flex items-start justify-between gap-4 mb-2">
                                <a href="{{ route('announcements.show', $announcement->id) }}"
                                    class="font-semibold text-gray-800 hover:text-blue-600 transition-colors text-sm">
                                    {{ $announcement->title }}
                                </a>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border
                                                                                                                                                                            {{ $announcement->priority === 'high' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                                                                                                                                                            {{ $announcement->priority === 'medium' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                                                                                                                                                            {{ $announcement->priority === 'low' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                                                                                                                                                        ">
                                    {{ $announcement->priority }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed mb-3">
                                {{ Str::limit($announcement->message, 150) }}
                            </p>
                            <div class="flex items-center justify-between text-[11px] text-gray-400">
                                <span>By <strong
                                        class="text-gray-600">{{ $announcement->creator->name ?? 'System' }}</strong></span>
                                <span>{{ $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->diffForHumans() : $announcement->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-sm">
                            <i class="fa-solid fa-bullhorn text-2xl mb-2 block"></i>
                            No recent announcements found.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ===== MY ASSIGNED TASKS ===== --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-amber-500 text-base"></i>
                        <h2 class="text-base font-semibold text-gray-800">My Assigned Tasks</h2>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View
                        All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
                                <th class="px-6 py-3 text-left font-medium">Task</th>
                                <th class="px-6 py-3 text-left font-medium">Project</th>
                                <th class="px-6 py-3 text-left font-medium">Deadline</th>
                                <th class="px-6 py-3 text-left font-medium">Priority</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($myTasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <a href="{{ route('tasks.show', $task->id) }}"
                                            class="font-semibold text-gray-800 hover:text-blue-600 transition-colors text-xs block">
                                            {{ $task->title }}
                                        </a>
                                        <span class="text-[10px] text-gray-400">Assigned by
                                            {{ $task->assigner->name ?? 'Manager' }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-600 text-xs font-medium">
                                        {{ $task->project->name ?? '—' }}{{ $task->project && $task->project->trashed() ? ' [Deleted]' : '' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-gray-600 text-xs">
                                        <span
                                            class="{{ $task->deadline && $task->deadline->isPast() ? 'text-red-600 font-bold' : '' }}">
                                            {{ $task->deadline ? $task->deadline->format('d M Y') : 'No Deadline' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border
                                                                                                                                                                                    {{ $task->priority === 'high' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                                                                                                                                                                    {{ $task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                                                                                                                                                                    {{ $task->priority === 'low' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                                                                                                                                                                ">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                                                                                                                                                    {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                                                                                                                                                                    {{ $task->status === 'testing' ? 'bg-purple-100 text-purple-700' : '' }}
                                                                                                                                                                                    {{ $task->status === 'working' ? 'bg-blue-100 text-blue-700' : '' }}
                                                                                                                                                                                    {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                                                                                                                                                ">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                                        <i class="fa-solid fa-list-check text-2xl mb-2 block"></i>
                                        No tasks assigned to you.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ===== RECENT EMPLOYEES ===== --}}
            @can('Employee-Index')
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-users text-blue-500 text-base"></i>
                            <h2 class="text-base font-semibold text-gray-800">Recently Added Employees</h2>
                        </div>
                        <a href="{{ route('employees.index') }}" class="text-sm text-blue-600 hover:underline font-medium">View
                            All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
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
                                                    <img src="{{ $emp->profile }}"
                                                        class="w-9 h-9 rounded-full object-cover border border-gray-200"
                                                        alt="{{ $emp->name }}">
                                                @else
                                                    <div
                                                        class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm">
                                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    {{-- <p class="font-medium text-gray-800 text-xs">{{ $emp->name }}</p> --}}
                                                    @if (auth()->user()->can('Employee-View'))
                                                        <a href="{{ route('employees.show', $emp->id) }}">
                                                            <p
                                                                class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                                                {{ $emp->name }}
                                                            </p>
                                                        </a>
                                                    @else
                                                        <span class="text-sm font-medium text-gray-800">
                                                            {{ $emp->name }}
                                                        </span>
                                                    @endif
                                                    <p class="text-[10px] text-gray-400">{{ $emp->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-gray-600 text-xs">
                                            {{ $emp->department->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @if ($emp->roles->isNotEmpty())
                                                <span
                                                    class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 text-indigo-600">
                                                    {{ $emp->roles->first()->name }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            {{-- @if ($emp->status === 'active')
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700">Active</span>
                                            @elseif ($emp->status === 'inactive')
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700">Inactive</span>
                                            @else
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-600">Terminated</span>
                                            @endif --}}
                                            <x-badge :value="$emp->status" />
                                        </td>
                                        @can('Employee-Edit')
                                            <td class="px-6 py-3">
                                                <a href="{{ route('employees.edit', $emp->id) }}"
                                                    class="p-1 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded transition-colors inline-block">
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

        </div>

        {{-- Right Panel (1/3) --}}
        <div class="flex flex-col gap-6">

            {{-- ===== QUICK PUNCH ATTENDANCE ===== --}}
            @can('Attendance-Marking')
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                            <i class="fa-solid fa-clock text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">Quick Attendance</h2>
                            <p class="text-[10px] text-gray-400">Mark your log for today</p>
                        </div>
                    </div>

                    @php
                        $hasPunchedIn = $todayAttendance && $todayAttendance->check_in_time;
                        $hasPunchedOut = $todayAttendance && $todayAttendance->check_out_time;
                    @endphp

                    <form action="{{ route('attendance.punch') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            @if ($hasPunchedIn)
                                <div
                                    class="bg-gray-50 border border-gray-100 p-3 rounded-lg flex items-center justify-between text-xs text-gray-600">
                                    <span>Check-In:
                                        <strong>{{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') }}</strong></span>
                                    @if ($hasPunchedOut)
                                        <span>Check-Out:
                                            <strong>{{ \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') }}</strong></span>
                                    @endif
                                </div>
                            @endif

                            @if (!$hasPunchedOut)
                                <div>
                                    <input type="text" name="note" placeholder="Add note (optional)..."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50">
                                </div>
                            @endif

                            @if (!$hasPunchedIn)
                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg font-semibold text-xs transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                    Punch In
                                </button>
                            @elseif(!$hasPunchedOut)
                                <button type="submit"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2.5 rounded-lg font-semibold text-xs transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    Punch Out
                                </button>
                            @else
                                <div
                                    class="w-full bg-green-50 border border-green-100 text-green-700 py-2.5 rounded-lg font-semibold text-xs flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-circle-check text-sm"></i>
                                    Attendance Logged For Today
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            @endcan

            {{-- Quick Navigation --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-yellow-500 text-sm"></i>
                    <h2 class="text-sm font-semibold text-gray-800">Quick Navigation</h2>
                </div>
                <div class="p-3 flex flex-col gap-1">

                    @can('Employee-Index')
                        <a href="{{ route('employees.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-users text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">All Employees</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-blue-400"></i>
                        </a>
                    @endcan

                    @can('Employee-Create')
                        <a href="{{ route('employees.create') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-user-plus text-blue-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Add Employee</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-blue-400"></i>
                        </a>
                    @endcan

                    @can('Department-Index')
                        <a href="{{ route('departments.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-building text-purple-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Departments</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-purple-400"></i>
                        </a>
                    @endcan

                    @can('Attendance-Index')
                        <a href="{{ route('attendance.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-teal-50 group-hover:bg-teal-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-calendar-check text-teal-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Attendance</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-teal-400"></i>
                        </a>
                    @endcan

                    @can('Leave-Index')
                        <a href="{{ route('leaves.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-orange-50 text-gray-700 hover:text-orange-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-orange-50 group-hover:bg-orange-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-person-walking-arrow-right text-orange-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Leaves</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-orange-400"></i>
                        </a>
                    @endcan

                    @can('Task-Index')
                        <a href="{{ route('tasks.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-amber-50 text-gray-700 hover:text-amber-700 transition-all group">
                            <div
                                class="w-7 h-7 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-list-check text-amber-500 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Tasks</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-amber-400"></i>
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
                            <span class="text-xs font-medium">Roles & Permissions</span>
                            <i
                                class="fa-solid fa-chevron-right ml-auto text-[10px] text-gray-300 group-hover:text-indigo-400"></i>
                        </a>
                    @endcan

                </div>
            </div>

            {{-- Logged In User Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fa-solid fa-circle-user text-blue-400 text-sm"></i>
                    <h2 class="text-sm font-semibold text-gray-800">Logged In As</h2>
                </div>
                <div class="flex items-center gap-3">
                    @if (auth()->user()->profile)
                        <img src="{{ auth()->user()->profile }}"
                            class="w-12 h-12 rounded-full object-cover border-2 border-blue-100"
                            alt="{{ auth()->user()->name }}">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg border-2 border-blue-50">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800 text-xs">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->roles->isNotEmpty())
                            <span
                                class="mt-1 inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-50 text-indigo-600">
                                {{ auth()->user()->roles->first()->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection