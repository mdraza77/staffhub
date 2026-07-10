@extends('layouts.main')

@section('title', 'Generate Monthly Payslips | StaffHub')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Generate Payslips</h1>
            <p class="text-sm text-gray-500 mt-1">Select the period to disburse monthly salaries</p>
        </div>
        <a href="{{ route('payroll.payslips.index') }}"
            class="text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1 font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="max-w-xl">
        <form action="{{ route('payroll.payslips.store') }}" method="POST" id="generate-form"
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Month selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Month <span
                            class="text-red-500">*</span></label>
                    <select name="month"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white @error('month') border-red-500 @enderror"
                        required>
                        <option value="">Choose Month</option>
                        @foreach ($months as $month)
                            <option value="{{ $month }}"
                                {{ old('month', now()->subMonth()->format('F')) === $month ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                    @error('month')
                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Year selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Year <span
                            class="text-red-500">*</span></label>
                    <select name="year"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white @error('year') border-red-500 @enderror"
                        required>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ old('year', now()->year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    @error('year')
                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-800 space-y-2">
                <p class="font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info"></i> How it works:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-xs text-blue-700">
                    <li>This will generate **unpaid** payslips for all active employees who have a configured salary
                        structure.</li>
                    <li>The system will automatically scan **attendance check-ins** and **approved leaves** for the selected
                        month to calculate working, present, and unpaid/paid leave days.</li>
                    <li>Unpaid leaves will automatically deduct from the gross salary using a prorated daily rate.</li>
                    <li>If a payslip already exists for that month and is marked as **Unpaid**, it will be
                        updated/regenerated. Existing **Paid** payslips will be skipped.</li>
                </ul>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('payroll.payslips.index') }}"
                    class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium text-sm">
                    Cancel
                </a>
                <button type="submit" id="submit-btn"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium text-sm shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate animate-spin hidden" id="spinner"></i>
                    <span>Generate Payslips</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#generate-form').on('submit', function() {
                $('#submit-btn').prop('disabled', true).addClass('opacity-75');
                $('#spinner').removeClass('hidden');
            });
        });
    </script>
@endpush
