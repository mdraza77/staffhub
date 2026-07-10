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
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('base_salary', 10, 2)->default(0.0);
            $table->decimal('hra', 10, 2)->default(0.0);  // House Rent Allowance
            $table->decimal('other_allowances', 10, 2)->default(0.0);
            $table->decimal('pf_deduction', 10, 2)->default(0.0);  // Provident Fund
            $table->decimal('tax_deduction', 10, 2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
