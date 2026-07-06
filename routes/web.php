<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
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

    // Reports Routes
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/employees', 'employeesReport')->name('employees');
        Route::get('/attendance', 'attendanceReport')->name('attendance');
        Route::get('/leaves', [ReportController::class, 'leaveReport'])->name('leaves');
        Route::get('/leave-types', [ReportController::class, 'leaveTypeReport'])->name('leave-types');
        Route::get('/tasks', [ReportController::class, 'taskReport'])->name('tasks');
        Route::get('/departments', [ReportController::class, 'departmentReport'])->name('departments');
    });
});

require __DIR__ . '/auth.php';
