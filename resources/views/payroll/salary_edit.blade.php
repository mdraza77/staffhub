@extends('layouts.main')

@section('title', 'Set Salary Structure | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Set Salary Structure</h1>
            <p class="text-sm text-gray-500 mt-1">Configure compensation details for {{ $user->name }}</p>
        </div>
        <a href="{{ route('payroll.salaries.index') }}"
            class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Cols: Form --}}
        <div class="lg:col-span-2">
            <form id="salary-form" action="{{ route('payroll.salaries.update', $user->id) }}" method="POST"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Earnings Card --}}
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-5 space-y-4">
                        <h3 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-green-600"></i> Monthly Earnings
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Base Salary (Basic) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 font-medium">₹</span>
                                <input type="number" step="0.01" name="base_salary" id="base_salary"
                                    value="{{ old('base_salary', $salaryStructure->base_salary ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">House Rent Allowance (HRA) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 font-medium">₹</span>
                                <input type="number" step="0.01" name="hra" id="hra"
                                    value="{{ old('hra', $salaryStructure->hra ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Other Allowances <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 font-medium">₹</span>
                                <input type="number" step="0.01" name="other_allowances" id="other_allowances"
                                    value="{{ old('other_allowances', $salaryStructure->other_allowances ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Deductions Card --}}
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-5 space-y-4">
                        <h3 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-calculator text-red-500"></i> Monthly Deductions
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provident Fund (PF) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 font-medium">₹</span>
                                <input type="number" step="0.01" name="pf_deduction" id="pf_deduction"
                                    value="{{ old('pf_deduction', $salaryStructure->pf_deduction ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Income Tax / Professional Tax <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 font-medium">₹</span>
                                <input type="number" step="0.01" name="tax_deduction" id="tax_deduction"
                                    value="{{ old('tax_deduction', $salaryStructure->tax_deduction ?? 0) }}"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    required>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('payroll.salaries.index') }}"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium text-sm shadow-sm">
                        Save Salary Structure
                    </button>
                </div>
            </form>
        </div>

        {{-- Right Col: Employee Card + Real-time Summary --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Employee Details --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">
                @if ($user->profile)
                    <img src="{{ asset('storage/' . $user->profile) }}" alt=""
                        class="w-24 h-24 rounded-full object-cover border-4 border-blue-50 shadow-sm mb-4">
                @else
                    <div
                        class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-2xl mb-4 border-4 border-blue-50 shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $user->designation ?? 'Employee' }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->department->name ?? 'Unassigned' }}</p>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Live Salary Overview</h4>

                <div class="space-y-2 border-b border-gray-100 pb-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Gross Earnings:</span>
                        <span class="font-bold text-green-700" id="summary-gross">₹0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Deductions:</span>
                        <span class="font-bold text-red-600" id="summary-deductions">₹0.00</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-bold text-gray-800">Estimated Net Salary:</span>
                    <span class="text-xl font-extrabold text-blue-600" id="summary-net">₹0.00</span>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateSummary() {
                let base = parseFloat($('#base_salary').val()) || 0;
                let hra = parseFloat($('#hra').val()) || 0;
                let allowances = parseFloat($('#other_allowances').val()) || 0;

                let pf = parseFloat($('#pf_deduction').val()) || 0;
                let tax = parseFloat($('#tax_deduction').val()) || 0;

                let gross = base + hra + allowances;
                let deductions = pf + tax;
                let net = gross - deductions;

                if (net < 0) net = 0;

                $('#summary-gross').text('₹' + gross.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#summary-deductions').text('₹' + deductions.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#summary-net').text('₹' + net.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }

            // Bind keyup and change events
            $('#base_salary, #hra, #other_allowances, #pf_deduction, #tax_deduction').on('keyup change input', updateSummary);

            // Run once on load
            updateSummary();
        });
    </script>
@endpush
