@extends('layouts.main')

@section('title', 'Employee Salary Structures | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Salary Structures</h1>
            <p class="text-sm text-gray-500 mt-1">Configure base earnings and deductions for your team</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="salaries" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="px-6 py-3 font-semibold">Employee</th>
                        <th class="px-6 py-3 font-semibold">Department & Title</th>
                        <th class="px-6 py-3 font-semibold">Base Salary</th>
                        <th class="px-6 py-3 font-semibold">HRA</th>
                        <th class="px-6 py-3 font-semibold">Other Allowances</th>
                        <th class="px-6 py-3 font-semibold">PF Deduction</th>
                        <th class="px-6 py-3 font-semibold">Tax Deduction</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($employees as $employee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Employee details --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($employee->profile)
                                        <img src="{{ $employee->profile }}" alt=""
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-200">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Department --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800 font-medium">{{ $employee->designation ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->department->name ?? 'Unassigned' }}</p>
                            </td>

                            {{-- Base Salary --}}
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                ₹{{ number_format($employee->salaryStructure->base_salary ?? 0, 2) }}
                            </td>

                            {{-- HRA --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                ₹{{ number_format($employee->salaryStructure->hra ?? 0, 2) }}
                            </td>

                            {{-- Allowances --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                ₹{{ number_format($employee->salaryStructure->other_allowances ?? 0, 2) }}
                            </td>

                            {{-- PF --}}
                            <td class="px-6 py-4 text-sm text-red-600">
                                ₹{{ number_format($employee->salaryStructure->pf_deduction ?? 0, 2) }}
                            </td>

                            {{-- Tax --}}
                            <td class="px-6 py-4 text-sm text-red-600">
                                ₹{{ number_format($employee->salaryStructure->tax_deduction ?? 0, 2) }}
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-center">
                                @can('Salary-Edit')
                                    <a href="{{ route('payroll.salaries.edit', $employee->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors border border-blue-100">
                                        <i class="fa-solid fa-pen-to-square"></i> Set Salary
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#salaries').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"l>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search employee...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });
    </script>
@endpush
