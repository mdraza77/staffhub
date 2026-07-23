<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->unique()->nullable();
            $table->string('phone_country_code', 5)->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_country_code', 5)->nullable();
            $table->string('emergency_contact')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('designation')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('profile')->nullable();
            $table->string('signature')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('blood_group', [
                'A+',
                'A-',
                'B+',
                'B-',
                'AB+',
                'AB-',
                'O+',
                'O-'
            ])->nullable();
            $table->string('address')->nullable();
            $table->softDeletes();
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
                'phone_country_code',
                'phone',
                'emergency_country_code',
                'emergency_contact',
                'department_id',
                'designation',
                'joining_date',
                'profile',
                'signature',
                'status',
                'gender',
                'date_of_birth',
                'blood_group',
                'address',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
