<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
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
        Route::delete('/{employee}/force-delete', 'forceDelete')->name('force-delete');
    });

    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/punch', [AttendanceController::class, 'punch'])->name('attendance.punch');

    // Leave Types Routes
    Route::resource('leave-types', LeaveTypeController::class)->except(['create', 'show', 'edit']);

    // Leaves Routes
    Route::controller(LeaveController::class)->prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{leave}/status', 'updateStatus')->name('updateStatus');
    });

    // Roles Routes
    Route::resource('roles', RoleController::class);

    // Tasks Routes
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::post('tasks/{task}/documents', [TaskController::class, 'storeDocument'])->name('tasks.documents.store');
    Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');

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
        Route::delete('/{announcement}/force-delete', 'forceDelete')->name('force-delete')->withTrashed();
    });
});

require __DIR__ . '/auth.php';
