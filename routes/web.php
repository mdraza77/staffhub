<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
    Route::resource('employees', EmployeeController::class);
});

require __DIR__ . '/auth.php';
