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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('month');  // e.g., 'January'
            $table->integer('year');  // e.g., 2026
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('paid_leaves')->default(0);
            $table->integer('unpaid_leaves')->default(0);
            $table->decimal('gross_salary', 10, 2)->default(0.0);
            $table->decimal('total_deductions', 10, 2)->default(0.0);
            $table->decimal('net_salary', 10, 2)->default(0.0);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
