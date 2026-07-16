<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakTypeController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeBreakController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
});

Route::middleware(['auth'])->group(function () {
    // Departments Routes
    Route::resource('departments', DepartmentController::class);
    Route::controller(DepartmentController::class)->prefix('departments')->name('departments.')->group(function () {
        Route::post('/{department}/restore', 'restore')->name('restore');
        Route::delete('/{department}/force-delete', 'forceDelete')->name('force-delete');
    });

    // Employees Routes
    Route::resource('employees', EmployeeController::class);
    Route::controller(EmployeeController::class)->prefix('employees')->name('employees.')->group(function () {
        Route::post('/{employee}/restore', 'restore')->name('restore');
        // Route::delete('/{employee}/force-delete', 'forceDelete')->name('force-delete');
    });

    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/punch', [AttendanceController::class, 'punch'])->name('attendance.punch');

    // Leave Types Routes
    Route::resource('leave-types', LeaveTypeController::class);
    Route::controller(LeaveTypeController::class)->prefix('leave-types')->name('leave-types.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{leave}/status', 'updateStatus')->name('updateStatus');
        Route::post('/{leave}/restore', 'restore')->name('restore');
    });

    // Leaves Routes
    Route::controller(LeaveController::class)->prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{leave}/status', 'updateStatus')->name('updateStatus');
    });

    // Roles Routes
    Route::resource('roles', RoleController::class);

    // Tasks Routes
    Route::resource('tasks', TaskController::class)->withTrashed();
    Route::post('tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore')->withTrashed();
    Route::post('tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store')->withTrashed();
    Route::post('tasks/{task}/documents', [TaskController::class, 'storeDocument'])->name('tasks.documents.store')->withTrashed();
    Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update')->withTrashed();

    // Reports Routes
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/employees', 'employeesReport')->name('employees');
        Route::get('/attendance', 'attendanceReport')->name('attendance');
        Route::get('/leaves', [ReportController::class, 'leaveReport'])->name('leaves');
        Route::get('/leave-types', [ReportController::class, 'leaveTypeReport'])->name('leave-types');
        Route::get('/tasks', [ReportController::class, 'taskReport'])->name('tasks');
        Route::get('/departments', [ReportController::class, 'departmentReport'])->name('departments');
    });

    // Holidays Routes
    Route::controller(HolidayController::class)->prefix('holidays')->name('holidays.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{holiday}', 'show')->name('show');
        Route::get('/{holiday}/edit', 'edit')->name('edit');
        Route::put('/{holiday}', 'update')->name('update');
        Route::delete('/{holiday}', 'destroy')->name('destroy');
        Route::post('/{holiday}/restore', 'restore')->name('restore')->withTrashed();
        Route::delete('/{holiday}/force-delete', 'forceDelete')->name('force-delete')->withTrashed();
    });

    // Payroll Routes
    Route::prefix('payroll')->name('payroll.')->group(function () {
        // Salary Structures
        Route::get('/salaries', [PayrollController::class, 'salaryIndex'])->name('salaries.index');
        Route::get('/salaries/{user}/edit', [PayrollController::class, 'salaryEdit'])->name('salaries.edit');
        Route::put('/salaries/{user}', [PayrollController::class, 'salaryUpdate'])->name('salaries.update');

        // Payslips
        Route::get('/payslips', [PayrollController::class, 'payslipIndex'])->name('payslips.index');
        Route::get('/payslips/generate', [PayrollController::class, 'payslipCreate'])->name('payslips.create');
        Route::post('/payslips/generate', [PayrollController::class, 'payslipStore'])->name('payslips.store');
        Route::get('/payslips/{payslip}', [PayrollController::class, 'payslipShow'])->name('payslips.show');
        Route::put('/payslips/{payslip}/status', [PayrollController::class, 'payslipStatusUpdate'])->name('payslips.status.update');
        Route::delete('/payslips/{payslip}', [PayrollController::class, 'payslipDestroy'])->name('payslips.destroy');
    });

    // Announcements Routes
    Route::controller(AnnouncementController::class)->prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{announcement}', 'show')->name('show');
        Route::get('/{announcement}/edit', 'edit')->name('edit');
        Route::put('/{announcement}', 'update')->name('update');
        Route::delete('/{announcement}', 'destroy')->name('destroy');
        Route::post('/{announcement}/restore', 'restore')->name('restore')->withTrashed();
    });

    // Defects Routes
    Route::controller(DefectController::class)->prefix('defects')->name('defects.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{defect}', 'show')->name('show')->withTrashed();
        Route::get('/{defect}/edit', 'edit')->name('edit')->withTrashed();
        Route::put('/{defect}', 'update')->name('update')->withTrashed();
        Route::delete('/{defect}', 'destroy')->name('destroy')->withTrashed();

        Route::post('/{defect}/restore', 'restore')->name('restore')->withTrashed();
        Route::post('/{defect}/status', 'updateStatus')->name('status.update')->withTrashed();
        Route::post('/{defect}/attachments', 'storeAttachment')->name('attachments.store')->withTrashed();
    });

    // Break Types Routes
    Route::controller(BreakTypeController::class)->prefix('break-types')->name('break-types.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{break_type}/edit', 'edit')->name('edit');
        Route::put('/{break_type}', 'update')->name('update');
        Route::delete('/{break_type}', 'destroy')->name('destroy');
        Route::post('/{break_type}/restore', 'restore')->name('restore')->withTrashed();
    });

    // Break Room Routes
    Route::controller(EmployeeBreakController::class)->prefix('break-room')->name('break-room.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/start', 'startBreak')->name('start');
        Route::post('/end', 'endBreak')->name('end');
    });
    Route::get('breaks-history', [EmployeeBreakController::class, 'history'])->name('breaks.history');

    // Company Settings Routes
    Route::get('company-settings', [CompanySettingController::class, 'edit'])->name('company');
    Route::put('company-settings', [CompanySettingController::class, 'update'])->name('company.update');
});

// For testing only
Route::middleware(['auth'])->get('/refresh-db-secret-123', function () {
    if (!auth()->user()->hasRole('Super Admin')) {
        return response()->json([
            'title' => 'Access Denied',
            'message' => 'Users with the role "' . auth()->user()->roles->first()->name . '" are not authorized to perform this action. Only Super Admins can refresh the database.'
        ]);
    }

    try {
        Artisan::call('migrate:fresh --seed --force');

        return response()->json([
            'success' => true,
            'message' => 'Database refreshed successfully.'
        ]);
    } catch (\Throwable $e) {
        Log::error($e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Database refresh failed.',
            'error' => $e->getMessage()
        ], 500);
    }
});

require __DIR__ . '/auth.php';
