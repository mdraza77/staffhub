@extends('layouts.main')

@section('title', 'Employees | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employees</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your workforce</p>
        </div>
        <a href="{{ route('employees.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Add Employee
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">ID & Role</th>
                        <th class="px-6 py-3 font-semibold">Department</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $key => $employee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $key + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($employee->profile)
                                        <img src="{{ asset('storage/' . $employee->profile) }}" alt=""
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-200">
                                            {{ substr($employee->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-800">{{ $employee->employee_id ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->designation ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $employee->department ? $employee->department->name : 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($employee->status === 'active')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                                @elseif($employee->status === 'inactive')
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">Inactive</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">Terminated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('employees.edit', $employee->id) }}"
                                    class="text-blue-600 hover:text-blue-800 mx-1 transition-colors" title="Edit"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <button class="text-red-600 hover:text-red-800 mx-1 transition-colors" title="Delete"><i
                                        class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No employees found. Click "Add Employee" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
@endsection
