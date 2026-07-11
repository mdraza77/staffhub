<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Employee Specific Columns (New Additions)
            $table->string('employee_id')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('designation')->nullable(); // e.g., Laravel Developer, HR Manager
            $table->date('joining_date')->nullable();
            $table->string('profile')->nullable(); // For profile image
            $table->string('signature')->nullable(); // For signature image
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->softDeletes(); // For soft deletion of employee records
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'phone',
                'department_id',
                'designation',
                'joining_date',
                'profile',
                'status'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
