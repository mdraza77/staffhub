<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payslip;
use App\Models\SalaryStructure;
use App\Models\User;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Salary-View', only: ['salaryIndex']),
            new Middleware('permission:Salary-Edit', only: ['salaryEdit', 'salaryUpdate']),
            new Middleware('permission:Payslip-Index', only: ['payslipIndex']),
            new Middleware('permission:Payslip-Create', only: ['payslipCreate', 'payslipStore']),
            new Middleware('permission:Payslip-Edit', only: ['payslipStatusUpdate']),
            new Middleware('permission:Payslip-View', only: ['payslipShow']),
            new Middleware('permission:Payslip-Delete', only: ['payslipDestroy']),
        ];
    }

    /**
     * List all employees and their salary structures.
     */
    public function salaryIndex()
    {
        $employees = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Super Admin');
        })->with('salaryStructure')->get();

        return view('payroll.salary_index', compact('employees'));
    }

    /**
     * Show edit form for an employee's salary structure.
     */
    public function salaryEdit(User $user)
    {
        $salaryStructure = $user->salaryStructure ?? new SalaryStructure();
        return view('payroll.salary_edit', compact('user', 'salaryStructure'));
    }

    /**
     * Update/Create salary structure for an employee.
     */
    public function salaryUpdate(Request $request, User $user)
    {
        $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'hra' => 'required|numeric|min:0',
            'other_allowances' => 'required|numeric|min:0',
            'pf_deduction' => 'required|numeric|min:0',
            'tax_deduction' => 'required|numeric|min:0',
        ]);

        $user->salaryStructure()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['base_salary', 'hra', 'other_allowances', 'pf_deduction', 'tax_deduction'])
        );

        return redirect()->route('payroll.salaries.index')->with('success', 'Salary structure updated successfully.');
    }

    /**
     * Display a listing of payslips.
     */
    public function payslipIndex(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('HR Manager')) {
            $payslips = Payslip::with('user')->latest()->get();
        } else {
            // For employee, only show their own payslips
            $payslips = Payslip::where('user_id', $user->id)->latest()->get();
        }

        return view('payroll.payslip_index', compact('payslips'));
    }

    /**
     * Show form to generate payslips.
     */
    public function payslipCreate()
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        $years = range(now()->year - 2, now()->year + 1);

        return view('payroll.payslip_create', compact('months', 'years'));
    }

    /**
     * Generate & store payslips for all employees for a selected month & year.
     */
    public function payslipStore(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Get month number
        $monthNum = Carbon::parse($month)->month;

        $startDate = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $monthNum, 1)->endOfMonth();

        // Calculate standard working days in the month (excluding Sundays)
        $workingDays = 0;
        $temp = clone $startDate;
        while ($temp->lte($endDate)) {
            if (!$temp->isSunday()) {
                $workingDays++;
            }
            $temp->addDay();
        }

        // Deduct active public and company holidays in the month (excluding Sundays to avoid double deduction)
        $holidays = Holiday::where('status', 'active')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate->format('Y-m-d'))
                            ->where('end_date', '>=', $endDate->format('Y-m-d'));
                    });
            })
            ->whereIn('type', ['public', 'company'])
            ->get();

        $holidayDays = 0;
        foreach ($holidays as $holiday) {
            $holidayStart = Carbon::parse($holiday->start_date);
            $holidayEnd = Carbon::parse($holiday->end_date ?? $holiday->start_date);

            $overlapStart = $holidayStart->max($startDate);
            $overlapEnd = $holidayEnd->min($endDate);

            $tempDay = clone $overlapStart;
            while ($tempDay->lte($overlapEnd)) {
                if (!$tempDay->isSunday()) {
                    $holidayDays++;
                }
                $tempDay->addDay();
            }
        }

        $workingDays = max(0, $workingDays - $holidayDays);

        // Get all employees who have a salary structure
        $employees = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Super Admin');
        })->whereHas('salaryStructure')->get();

        if ($employees->isEmpty()) {
            return back()->with('error', 'No employees found with a configured salary structure.');
        }

        DB::beginTransaction();
        try {
            $generatedCount = 0;

            foreach ($employees as $employee) {
                // Check if a paid payslip already exists for this month/year.
                $exists = Payslip::where('user_id', $employee->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->first();

                if ($exists && $exists->status === 'paid') {
                    continue;  // Skip paid payslips
                }

                // If exists and unpaid, delete to regenerate
                if ($exists) {
                    $exists->delete();
                }

                // 1. Calculate present days (attendance count)
                $presentDays = Attendance::where('user_id', $employee->id)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->count();

                // Cap present days to working days
                if ($presentDays > $workingDays) {
                    $presentDays = $workingDays;
                }

                // 2. Calculate paid & unpaid leaves
                $leaves = Leave::where('user_id', $employee->id)
                    ->where('status', 'approved')
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q
                            ->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                            ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                            ->orWhere(function ($q2) use ($startDate, $endDate) {
                                $q2
                                    ->where('start_date', '<=', $startDate->format('Y-m-d'))
                                    ->where('end_date', '>=', $endDate->format('Y-m-d'));
                            });
                    })
                    ->with('leaveType')
                    ->get();

                $paidLeaves = 0;
                $unpaidLeaves = 0;

                foreach ($leaves as $leave) {
                    $leaveStart = Carbon::parse($leave->start_date);
                    $leaveEnd = Carbon::parse($leave->end_date);

                    $overlapStart = $leaveStart->max($startDate);
                    $overlapEnd = $leaveEnd->min($endDate);
                    $overlapDays = $overlapStart->diffInDays($overlapEnd) + 1;

                    if ($leave->leaveType && $leave->leaveType->name === 'Unpaid Leave') {
                        $unpaidLeaves += $overlapDays;
                    } else {
                        $paidLeaves += $overlapDays;
                    }
                }

                // If present days + paid leaves + unpaid leaves < working days, and no other records exist,
                // we can adjust present_days to equal (working_days - unpaid_leaves - paid_leaves) to be fair,
                // but let's keep it strictly based on check-ins + leaves.
                // Standard check: present_days + paid_leaves + unpaid_leaves should not exceed working days.

                // 3. Compute salary details
                $salary = $employee->salaryStructure;
                $grossSalary = $salary->base_salary + $salary->hra + $salary->other_allowances;
                $totalDeductions = $salary->pf_deduction + $salary->tax_deduction;

                // NAYA LOGIC: Calculate Total Absent Days
                // Absent = Total Working Days mein se (Present Days + Paid Leaves) nikal do
                $absentDays = $workingDays - ($presentDays + $paidLeaves);

                // Safety check (Negative nahi hona chahiye)
                if ($absentDays < 0) {
                    $absentDays = 0;
                }

                // Unpaid Deduction Calculation (Per day salary * Absent Days)
                $unpaidDeduction = 0;
                if ($absentDays > 0 && $workingDays > 0) {
                    $unpaidDeduction = round(($grossSalary / $workingDays) * $absentDays, 2);
                }

                // Net Salary = Gross - Absent Deduction - PF/Tax
                $netSalary = $grossSalary - $unpaidDeduction - $totalDeductions;

                if ($netSalary < 0) {
                    $netSalary = 0;
                }

                Payslip::create([
                    'user_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'working_days' => $workingDays,
                    'present_days' => $presentDays,
                    'paid_leaves' => $paidLeaves,
                    'unpaid_leaves' => $unpaidLeaves,
                    'gross_salary' => $grossSalary,
                    'total_deductions' => $totalDeductions + $unpaidDeduction,
                    'net_salary' => $netSalary,
                    'status' => 'unpaid',
                ]);

                $generatedCount++;
            }

            DB::commit();

            if ($generatedCount === 0) {
                return redirect()->route('payroll.payslips.index')->with('info', 'No new payslips were generated (they might already exist and be marked as paid).');
            }

            return redirect()->route('payroll.payslips.index')->with('success', "$generatedCount payslip(s) generated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error generating payslips: ' . $e->getMessage());
        }
    }

    /**
     * Show details of a single payslip.
     */
    public function payslipShow(Payslip $payslip)
    {
        $user = auth()->user();

        // Check permission: employees can only view their own payslips
        if (!$user->hasRole('Super Admin') && !$user->hasRole('Admin') && !$user->hasRole('HR Manager') && $payslip->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get salary structure for reference
        $salaryStructure = $payslip->user->salaryStructure;

        // Fetch HR Manager and Admin for signatures
        $hrManager = User::whereHas('roles', function ($q) {
            $q->where('name', 'HR Manager');
        })
            ->whereNotNull('signature')
            ->first();

        $adminUser = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        })
            ->whereNotNull('signature')
            ->first();

        return view('payroll.payslip_show', compact('payslip', 'salaryStructure', 'hrManager', 'adminUser'));
    }

    /**
     * Update status (Paid/Unpaid) of a payslip.
     */
    public function payslipStatusUpdate(Request $request, Payslip $payslip)
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid',
        ]);

        $payslip->update(['status' => $request->status]);

        return back()->with('success', 'Payslip status updated successfully.');
    }

    /**
     * Delete a payslip.
     */
    public function payslipDestroy(Payslip $payslip)
    {
        $payslip->delete();
        return redirect()->route('payroll.payslips.index')->with('success', 'Payslip deleted successfully.');
    }
}
