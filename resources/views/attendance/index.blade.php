@extends('layouts.main')

@section('title', 'Attendance | StaffHub')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daily Attendance</h1>
            <p class="text-sm text-gray-500 mt-1">Track and manage employee check-ins and check-outs.</p>
        </div>

        <form action="{{ route('attendance.punch') }}" method="POST" class="w-full md:w-auto">
            @csrf

            @php
                $hasPunchedIn = $todayAttendance && $todayAttendance->check_in_time;
                $hasPunchedOut = $todayAttendance && $todayAttendance->check_out_time;
            @endphp
            @can('Attendance-Marking')
                <div class="flex flex-col items-end gap-2">

                    @if (!$hasPunchedOut)
                        <div class="w-full md:w-72">
                            <input type="text" name="note"
                                placeholder="Remark (e.g., {{ $hasPunchedIn ? 'Leaving early due to doctor visit' : 'Late due to traffic' }})"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-gray-400 bg-gray-50">
                        </div>
                    @endif

                    {{-- Dynamic Button States --}}
                    @if (!$hasPunchedIn)
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 w-full">
                            <i class="fa-solid fa-right-to-bracket text-lg"></i>
                            <span>Punch In</span>
                        </button>
                    @elseif(!$hasPunchedOut)
                        <button type="submit"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 w-full">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            <span>Punch Out</span>
                        </button>
                    @else
                        <button type="button" disabled
                            class="bg-gray-100 border border-gray-200 text-gray-400 px-6 py-2.5 rounded-lg font-bold cursor-not-allowed flex items-center justify-center gap-2 w-full">
                            <i class="fa-solid fa-calendar-check text-lg"></i>
                            <span>Done for Today</span>
                        </button>
                    @endif

                </div>
            @endcan
        </form>
    </div>

    {{-- @if (session('success'))
        <div
            class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif --}}

    {{-- <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex items-center justify-between">
        <div class="font-medium text-gray-700">
            <i class="fa-regular fa-calendar text-blue-600 mr-2"></i>
            Showing records for: <span
                class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</span>
        </div>

        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}"
                class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors border border-gray-200">
                Filter
            </button>
            @if (request('date'))
                <a href="{{ route('attendance.index') }}" class="text-xs text-red-500 hover:underline ml-2">Clear</a>
            @endif
        </form>
    </div> --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="attendance" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">Department</th>
                        <th class="px-6 py-3 font-semibold">Date</th>
                        <th class="px-6 py-3 font-semibold text-center">Check-In</th>
                        <th class="px-6 py-3 font-semibold text-center">Check-Out</th>
                        <th class="px-6 py-3 font-semibold text-center">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($attendance->user->profile)
                                        <img src="{{ asset('storage/' . $attendance->user->profile) }}" alt=""
                                            class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-200">
                                            {{ substr($attendance->user->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $attendance->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $attendance->user->employee_id ?? 'No ID' }}
                                        </p>
                                    </div>
                                </div>
                            </td> --}}
                            <td class="px-6 py-4 text-sm text-gray-600 font-bold text-xs">
                                {{ $attendance->user?->name ?? 'Deleted User' }}
                                ({{ $attendance->user?->employee_id ?? ' ' }})
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $attendance->user?->department ? $attendance->user->department->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('D d, M, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($attendance->check_in_time)
                                    <span
                                        class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-medium border border-green-200">
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">--:--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($attendance->check_out_time)
                                    <span
                                        class="bg-orange-50 text-orange-700 px-2 py-1 rounded text-xs font-medium border border-orange-200">
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">--:--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs px-3 py-1">
                                    {{ filled($attendance->note) ? $attendance->note : 'No Note' }}
                                </span>
                            </td>
                        </tr>
                        {{-- @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-base font-medium text-gray-600">No attendance records found for this
                                        date.</p>
                                    <p class="text-sm">Employees haven't punched in yet.</p>
                                </div>
                            </td>
                        </tr> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#attendance').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"lB>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
                buttons: [{
                        extend: 'copy',
                        className: 'bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 mr-2 transition-colors'
                    },
                    {
                        extend: 'excel',
                        className: 'bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-green-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'pdf',
                        className: 'bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-red-200 mr-2 transition-colors'
                    },
                    {
                        extend: 'print',
                        className: 'bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 transition-colors'
                    }
                ],
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search here...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });
    </script>
@endpush
