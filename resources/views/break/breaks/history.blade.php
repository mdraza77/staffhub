@extends('layouts.main')

@section('title', 'Break History | StaffHub')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i> Break History
                </h1>
                <p class="text-sm text-gray-500">
                    {{ $isAdminOrHR ? 'Audit employee refreshment breaks and durations.' : 'Track your historical lounge breaks.' }}
                </p>
            </div>
            <a href="{{ route('break-room.index') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-1.5 shadow">
                <i class="fa-solid fa-mug-hot"></i> Enter Break Room
            </a>
        </div>

        @if ($isAdminOrHR)
            <!-- Admin Filter Console -->
            <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-8">
                <form action="{{ route('breaks.history') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Employee Filter -->
                    <div>
                        <label for="user_id"
                            class="block text-xs font-bold text-gray-750 mb-1.5 uppercase">Employee</label>
                        <select name="user_id" id="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Employees</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Break Type Filter -->
                    <div>
                        <label for="break_type_id" class="block text-xs font-bold text-gray-750 mb-1.5 uppercase">Break
                            Type</label>
                        <select name="break_type_id" id="break_type_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Types</option>
                            @foreach ($breakTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('break_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label for="date" class="block text-xs font-bold text-gray-750 mb-1.5 uppercase">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Action buttons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-1 border border-gray-200">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>
                        <a href="{{ route('breaks.history') }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-1 border border-gray-200">
                            <i class="fa-solid fa-rotate-right text-xs"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <!-- Logs Table -->
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse" id="breaks-history-table">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            @if ($isAdminOrHR)
                                <th class="px-6 py-3 font-semibold">Employee</th>
                            @endif
                            <th class="px-6 py-3 font-semibold">Break Category</th>
                            <th class="px-6 py-3 font-semibold">Started At</th>
                            <th class="px-6 py-3 font-semibold">Ended At</th>
                            <th class="px-6 py-3 font-semibold text-center">Duration</th>
                            <th class="px-6 py-3 font-semibold text-center">Status</th>
                            <th class="px-6 py-3 font-semibold">Note / Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($breaks as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if ($isAdminOrHR)
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div
                                                class="w-7 h-7 rounded-full bg-gray-100 text-gray-650 flex items-center justify-center font-bold text-[9px] border border-gray-200">
                                                {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="font-bold text-gray-800 block leading-tight">{{ $log->user->name }}</span>
                                                <span
                                                    class="text-[10px] text-gray-400 block">{{ $log->user->department->name ?? 'Staff' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-gray-700">
                                        <i
                                            class="{{ $log->breakType->icon ?? 'fa-solid fa-mug-hot' }} text-indigo-500"></i>
                                        {{ $log->breakType->name ?? 'Deleted Category' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span
                                        class="block leading-tight font-medium">{{ $log->started_at->format('M d, Y') }}</span>
                                    <span
                                        class="text-[10px] text-gray-400 block">{{ $log->started_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if ($log->ended_at)
                                        <span
                                            class="block leading-tight font-medium">{{ $log->ended_at->format('M d, Y') }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 block">{{ $log->ended_at->format('h:i A') }}</span>
                                    @else
                                        <span class="text-xs text-gray-450 italic">Ongoing</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($log->ended_at)
                                        @php
                                            $diffSecs = $log->started_at->diffInSeconds($log->ended_at);
                                            $diffMins = floor($diffSecs / 60);
                                        @endphp
                                        <span
                                            class="bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded text-xs border border-indigo-100">
                                            {{ $diffMins }} mins
                                        </span>
                                    @else
                                        <span
                                            class="bg-green-50 text-green-700 font-bold px-2.5 py-1 rounded text-xs border border-green-100 animate-pulse">
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($log->status === 'completed')
                                        <span
                                            class="bg-green-100 text-green-700 text-xs px-2.5 py-0.5 rounded-full font-semibold border border-green-200">Completed</span>
                                    @elseif($log->status === 'auto_completed')
                                        <span
                                            class="bg-gray-100 text-gray-700 text-xs px-2.5 py-0.5 rounded-full font-semibold border border-gray-200">Auto
                                            Completed</span>
                                    @else
                                        <span
                                            class="bg-yellow-105 text-yellow-750 text-xs px-2.5 py-0.5 rounded-full font-semibold border border-yellow-200 animate-pulse">Ongoing</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 italic max-w-xs truncate">
                                    {{ $log->remark ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdminOrHR ? '7' : '6' }}" class="px-6 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-clock-rotate-left text-3xl"></i>
                                        <span>No break logs recorded for this view.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($breaks->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $breaks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
