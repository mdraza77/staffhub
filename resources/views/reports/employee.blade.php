@extends('layouts.main')

@section('title', 'Employees Report | StaffHub')

@section('content')

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employees Report</h1>
            <p class="text-sm text-gray-500 mt-1">Filter and view detailed employee records</p>
        </div>
        <x-back-button :url="route('employees.index')" label="Back to Employees" />
    </div>

    {{-- ===== FILTER CARD ===== --}}
    <form method="GET" action="{{ route('reports.employees') }}" id="filter-form">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">

            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-filter text-blue-500 text-sm"></i>
                <h3 class="text-sm font-semibold text-gray-700">Filter Employees</h3>
                @if (request()->hasAny(['department_id', 'role', 'status', 'joining_from', 'joining_to', 'search']))
                    <a href="{{ route('reports.employees') }}"
                        class="ml-auto text-xs text-red-500 hover:text-red-700 flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-xmark"></i> Clear Filters
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

                {{-- Search --}}
                <div class="xl:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <div class="relative">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Name, email, ID, designation..."
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                {{-- Department --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Department</label>
                    <select name="department_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">All Departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                    <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated
                        </option>
                        <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>

                {{-- Filter Button --}}
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                        <i class="fa-solid fa-filter mr-1"></i> Apply
                    </button>
                </div>

            </div>

            {{-- Joining Date Range --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">
                        <i class="fa-solid fa-calendar mr-1"></i> Joining Date From
                    </label>
                    <input type="date" name="joining_from"
                        value="{{ request('joining_from', now()->subMonths(3)->format('Y-m-d')) }}""
                                class=" w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2
                        focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">
                        <i class="fa-solid fa-calendar mr-1"></i> Joining Date To
                    </label>
                    <input type="date" name="joining_to" value="{{ request('joining_to', now()->format('Y-m-d')) }}""
                                class=" w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2
                        focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>

        </div>
    </form>

    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total</p>
                <p class="text-xl font-bold text-gray-800">{{ $summary['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Active</p>
                <p class="text-xl font-bold text-gray-800">{{ $summary['active'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-pause text-yellow-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Inactive</p>
                <p class="text-xl font-bold text-gray-800">{{ $summary['inactive'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fa-solid fa-user-xmark text-red-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Terminated</p>
                <p class="text-xl font-bold text-gray-800">{{ $summary['terminated'] }}</p>
            </div>
        </div>
    </div>

    {{-- ===== REPORT TABLE ===== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-4">
        <div class="overflow-x-auto">
            <table id="report-table" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">Employee</th>
                        <th class="px-4 py-3 font-semibold">Employee ID</th>
                        <th class="px-4 py-3 font-semibold">Designation</th>
                        <th class="px-4 py-3 font-semibold">Department</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold">Joining Date</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($employees as $key => $employee)
                        <tr class="hover:bg-gray-50 transition-colors {{ $employee->trashed() ? 'opacity-60' : '' }}">

                            <td class="px-4 py-3 text-sm text-gray-500">{{ $key + 1 }}</td>

                            {{-- Employee --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($employee->profile)
                                        <img src="{{ asset('storage/' . $employee->profile) }}" alt="{{ $employee->name }}"
                                            class="w-9 h-9 rounded-full object-cover border border-gray-200 {{ $employee->trashed() ? 'grayscale' : '' }}">
                                    @else
                                        <div
                                            class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border
                                                                    {{ $employee->trashed() ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-600 border-blue-200' }}">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if (auth()->user()->can('Employee-View'))
                                    <a href="{{ route('employees.show', $employee->id) }}">
                                        <p class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                            {{ $employee->employee_id ?? '—' }}
                                        </p>
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $employee->employee_id ?? '—' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->designation ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $employee->department->name ?? '—' }}</td>

                            {{-- Role --}}
                            <td class="px-4 py-3">
                                @if ($employee->roles->isNotEmpty())
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $employee->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Joining Date --}}
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') : '—' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if ($employee->trashed())
                                    <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Deleted
                                    </span>
                                @elseif ($employee->status === 'active')
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Active
                                    </span>
                                @elseif ($employee->status === 'inactive')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Inactive
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Terminated
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <style>
        .dt-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            border-radius: 0.5rem;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.15s;
            margin-right: 4px;
            box-shadow: none !important;
        }

        .dt-btn-gray {
            background: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
        }

        .dt-btn-gray:hover {
            background: #e5e7eb;
        }

        .dt-btn-green {
            background: #f0fdf4;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .dt-btn-green:hover {
            background: #dcfce7;
        }

        .dt-btn-red {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .dt-btn-red:hover {
            background: #fee2e2;
        }

        .dt-btn-blue {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .dt-btn-blue:hover {
            background: #dbeafe;
        }

        .dataTables_filter input {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            outline: none !important;
            margin-left: 6px;
        }

        .dataTables_filter input:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15) !important;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dataTables_length select {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.375rem 2rem 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            outline: none !important;
            background-color: #fff;
            cursor: pointer;
            margin: 0 6px;
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.65rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e5e7eb !important;
            background: #fff !important;
            color: #374151 !important;
            transition: all 0.15s;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
            color: #2563eb !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .dataTables_info {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        table.dataTable thead th {
            border-bottom: none !important;
        }
    </style>
@endpush