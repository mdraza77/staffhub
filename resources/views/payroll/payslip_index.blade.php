@extends('layouts.main')

@section('title', 'Employee Payslips | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Monthly Payslips</h1>
            <p class="text-sm text-gray-500 mt-1">View, track, and download salary slips</p>
        </div>
        @can('Payslip-Create')
            <a href="{{ route('payroll.payslips.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar"></i> Generate Payslips
            </a>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="payslips" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('HR Manager'))
                            <th class="px-6 py-3 font-semibold">Employee</th>
                        @endif
                        <th class="px-6 py-3 font-semibold">Period</th>
                        <th class="px-6 py-3 font-semibold">Working Days</th>
                        <th class="px-6 py-3 font-semibold">Present Days</th>
                        <th class="px-6 py-3 font-semibold">Leaves (Paid/Unpaid)</th>
                        <th class="px-6 py-3 font-semibold">Net Salary</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($payslips as $payslip)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Employee details (Admin view only) --}}
                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('HR Manager'))
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($payslip->user->profile)
                                            <img src="{{ asset('storage/' . $payslip->user->profile) }}" alt=""
                                                class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-200">
                                                {{ strtoupper(substr($payslip->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $payslip->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $payslip->user->employee_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                            @endif

                            {{-- Month & Year --}}
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $payslip->month }} {{ $payslip->year }}
                            </td>

                            {{-- Working Days --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payslip->working_days }}
                            </td>

                            {{-- Present Days --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payslip->present_days }}
                            </td>

                            {{-- Leaves --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="text-green-700 font-semibold">{{ $payslip->paid_leaves }} Paid</span> /
                                <span class="text-red-600 font-semibold">{{ $payslip->unpaid_leaves }} Unpaid</span>
                            </td>

                            {{-- Net Salary --}}
                            <td class="px-6 py-4 text-sm font-bold text-blue-700">
                                ₹{{ number_format($payslip->net_salary, 2) }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if ($payslip->status === 'paid')
                                    <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-150 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Paid
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-700 border border-yellow-150 rounded-full text-xs font-semibold inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Unpaid
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- View Details --}}
                                    @can('Payslip-View')
                                        <a href="{{ route('payroll.payslips.show', $payslip->id) }}" title="View / Print Salary Slip"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="fa-solid fa-eye text-base"></i>
                                        </a>
                                    @endcan

                                    {{-- Update Status (Admin only) --}}
                                    @can('Payslip-Edit')
                                        <form action="{{ route('payroll.payslips.status.update', $payslip->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            @if ($payslip->status === 'unpaid')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" title="Mark as Paid"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-check-double text-base"></i>
                                                </button>
                                            @else
                                                <input type="hidden" name="status" value="unpaid">
                                                <button type="submit" title="Mark as Unpaid"
                                                    class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                                    <i class="fa-solid fa-undo text-base"></i>
                                                </button>
                                            @endif
                                        </form>
                                    @endcan

                                    {{-- Delete (Admin only) --}}
                                    @can('Payslip-Delete')
                                        <form action="{{ route('payroll.payslips.destroy', $payslip->id) }}" method="POST"
                                            id="delete-payslip-form-{{ $payslip->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $payslip->id }}, '{{ $payslip->user->name }}', '{{ $payslip->month }} {{ $payslip->year }}')"
                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-trash text-base"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
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
            $('#payslips').DataTable({
                destroy: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4 p-4"<"flex items-center gap-3"l>f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4 p-4 border-t border-gray-100"<"flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600"i><"flex items-center"p>>',
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search period/employee...",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });

        function confirmDelete(id, name, period) {
            Swal.fire({
                title: 'Delete Payslip?',
                html: `<span class="text-gray-600">Are you sure you want to delete the payslip of <strong>${name}</strong> for <strong>${period}</strong>?</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-payslip-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
