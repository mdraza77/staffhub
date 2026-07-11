@extends('layouts.main')

@section('title', 'Salary Slip | StaffHub')

@section('content')
    @php
        if (!function_exists('convertNumberToWords')) {
            function convertNumberToWords($number)
            {
                $no = floor($number);
                $point = round($number - $no, 2) * 100;
                $hundred = null;
                $digits_1 = strlen($no);
                $i = 0;
                $str = [];
                $words = [
                    0 => '',
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                    4 => 'Four',
                    5 => 'Five',
                    6 => 'Six',
                    7 => 'Seven',
                    8 => 'Eight',
                    9 => 'Nine',
                    10 => 'Ten',
                    11 => 'Eleven',
                    12 => 'Twelve',
                    13 => 'Thirteen',
                    14 => 'Fourteen',
                    15 => 'Fifteen',
                    16 => 'Sixteen',
                    17 => 'Seventeen',
                    18 => 'Eighteen',
                    19 => 'Nineteen',
                    20 => 'Twenty',
                    30 => 'Thirty',
                    40 => 'Forty',
                    50 => 'Fifty',
                    60 => 'Sixty',
                    70 => 'Seventy',
                    80 => 'Eighty',
                    90 => 'Ninety',
                ];
                $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
                while ($i < $digits_1) {
                    $divider = $i == 2 ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += $divider == 10 ? 1 : 2;
                    if ($number) {
                        $plural = ($counter = count($str)) && $number > 9 ? 's' : null;
                        $hundred = $counter == 1 && $str[0] ? ' and ' : null;
                        $str[] =
                            $number < 21
                                ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                                : $words[floor($number / 10) * 10] .
                                    ' ' .
                                    $words[$number % 10] .
                                    ' ' .
                                    $digits[$counter] .
                                    $plural .
                                    ' ' .
                                    $hundred;
                    } else {
                        $str[] = null;
                    }
                }
                $Rupees = implode('', array_reverse($str));
                $paise =
                    $point > 0 ? 'and ' . ($words[floor($point / 10) * 10] . ' ' . $words[$point % 10]) . ' Paise' : '';
                return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . ' Only';
            }
        }
    @endphp

    {{-- Controls --}}
    <div class="mb-6 flex justify-between items-center no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payslip Details</h1>
            <p class="text-sm text-gray-500 mt-1">Review employee salary slip for {{ $payslip->month }} {{ $payslip->year }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i> Print Payslip
            </button>
            <a href="{{ route('payroll.payslips.index') }}"
                class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    {{-- Payslip Container --}}
    <div id="payslip-card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-4xl mx-auto print-card">

        {{-- Header block --}}
        <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-blue-700 tracking-tight">StaffHub Technologies</h2>
                <p class="text-xs text-gray-400 mt-1">123 Business Hub, Sector-62, Noida, UP, India</p>
                <p class="text-xs text-gray-400">Email: payroll@staffhub.com | Web: www.staffhub.com</p>
            </div>
            <div class="text-right">
                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wider">Salary Slip</h3>
                <p class="text-sm text-gray-500 font-semibold mt-1">{{ $payslip->month }} - {{ $payslip->year }}</p>
                <span
                    class="mt-2 inline-block text-xs font-bold px-3 py-1 rounded-full uppercase
                    {{ $payslip->status === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                    {{ $payslip->status }}
                </span>
            </div>
        </div>

        {{-- Employee & Attendance Details Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-gray-200 pb-6 text-sm">
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Employee Information</h4>
                <table class="w-full text-left space-y-1">
                    <tr>
                        <td class="text-gray-400 font-medium py-1 w-1/3">Employee Name:</td>
                        <td class="text-gray-800 font-bold py-1">{{ $payslip->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Employee ID:</td>
                        <td class="text-gray-700 font-semibold py-1">{{ $payslip->user->employee_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Designation:</td>
                        <td class="text-gray-700 py-1">{{ $payslip->user->designation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Department:</td>
                        <td class="text-gray-700 py-1">{{ $payslip->user->department->name ?? 'Unassigned' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Phone Number:</td>
                        <td class="text-gray-700 py-1">{{ $payslip->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Joining Date:</td>
                        <td class="text-gray-700 py-1">
                            {{ $payslip->user->joining_date ? \Carbon\Carbon::parse($payslip->user->joining_date)->format('d M Y') : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Attendance Metrics</h4>
                <table class="w-full text-left space-y-1">
                    <tr>
                        <td class="text-gray-400 font-medium py-1 w-1/3">Working Days:</td>
                        <td class="text-gray-800 font-bold py-1">{{ $payslip->working_days }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Present Days:</td>
                        <td class="text-gray-700 font-semibold py-1">{{ $payslip->present_days }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Paid Leaves:</td>
                        <td class="text-green-700 font-semibold py-1">{{ $payslip->paid_leaves }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Unpaid Leaves:</td>
                        <td class="text-red-600 font-semibold py-1">{{ $payslip->unpaid_leaves }}</td>
                    </tr>
                    @php
                        $absentDays = max(0, $payslip->working_days - ($payslip->present_days + $payslip->paid_leaves));
                    @endphp
                    <tr>
                        <td class="text-gray-400 font-medium py-1">Absent Days:</td>
                        <td class="text-red-600 font-bold py-1">{{ $absentDays }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Breakdown Table --}}
        <div class="grid grid-cols-1 md:grid-cols-2 border border-gray-200 rounded-xl overflow-hidden mb-8 text-sm">

            {{-- Earnings Block --}}
            <div class="border-b md:border-b-0 md:border-r border-gray-200">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 font-bold text-gray-800 flex justify-between">
                    <span>Earnings</span>
                    <span>Amount</span>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Basic Salary</span>
                        <span
                            class="font-semibold text-gray-800">₹{{ number_format($salaryStructure->base_salary ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">House Rent Allowance (HRA)</span>
                        <span
                            class="font-semibold text-gray-800">₹{{ number_format($salaryStructure->hra ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Other Allowances</span>
                        <span
                            class="font-semibold text-gray-800">₹{{ number_format($salaryStructure->other_allowances ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Deductions Block --}}
            <div>
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 font-bold text-gray-800 flex justify-between">
                    <span>Deductions</span>
                    <span>Amount</span>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Provident Fund (PF)</span>
                        <span
                            class="font-semibold text-red-600">₹{{ number_format($salaryStructure->pf_deduction ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Income Tax / Prof. Tax</span>
                        <span
                            class="font-semibold text-red-600">₹{{ number_format($salaryStructure->tax_deduction ?? 0, 2) }}</span>
                    </div>

                    {{-- Unpaid Leave/Absent Deduction calculated dynamically --}}
                    @php
                        $unpaidLeaveDeduction = 0;
                        $standardDeductions =
                            ($salaryStructure->pf_deduction ?? 0) + ($salaryStructure->tax_deduction ?? 0);
                        if ($payslip->total_deductions > $standardDeductions) {
                            $unpaidLeaveDeduction = $payslip->total_deductions - $standardDeductions;
                        }
                    @endphp
                    @if ($unpaidLeaveDeduction > 0)
                        <div class="flex justify-between">
                            <div>
                                <span class="text-red-500 font-medium block">Absent / LWP Deduction</span>
                                <span class="text-[10px] text-gray-400 font-normal">({{ $absentDays }} day(s)
                                    absent)</span>
                            </div>
                            <span class="font-bold text-red-600">₹{{ number_format($unpaidLeaveDeduction, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Totals Summary --}}
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 text-sm space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-600 font-medium">Gross Salary (A):</span>
                <span class="font-bold text-gray-800">₹{{ number_format($payslip->gross_salary, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-medium">Total Deductions (B):</span>
                <span class="font-bold text-red-600">₹{{ number_format($payslip->total_deductions, 2) }}</span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                <span class="text-base font-bold text-gray-800">Net Salary Payable (A - B):</span>
                <span class="text-2xl font-extrabold text-blue-700">₹{{ number_format($payslip->net_salary, 2) }}</span>
            </div>
        </div>

        {{-- Net Salary in Words --}}
        <div class="text-sm text-gray-500 border-b border-gray-100 pb-6 mb-8">
            <span class="font-bold text-gray-700">In Words:</span>
            <span class="italic font-medium text-gray-600 ml-1">{{ convertNumberToWords($payslip->net_salary) }}</span>
        </div>

        {{-- Signatures --}}
        <div
            class="flex justify-between items-center mt-12 pt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
            <div class="w-1/3">
                <div class="border-b border-gray-300 h-10 mb-2"></div>
                <p>Employee Signature</p>
            </div>
            <div class="w-1/3">
                <div class="border-b border-gray-300 h-10 mb-2"></div>
                <p>Prepared By (HR Manager)</p>
            </div>
            <div class="w-1/3">
                <div class="border-b border-gray-300 h-10 mb-2"></div>
                <p>Authorized Signatory</p>
            </div>
        </div>

    </div>

    {{-- Styling for print mode --}}
    <style>
        @media print {

            /* Hide everything in layout except our payslip card */
            body * {
                visibility: hidden;
            }

            #payslip-card,
            #payslip-card * {
                visibility: visible;
            }

            /* Position card at the top-left */
            #payslip-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                max-width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
