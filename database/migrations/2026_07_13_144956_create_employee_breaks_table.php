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
        Schema::create('employee_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('break_type_id')->constrained('break_types')->cascadeOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('expected_end_time');
            $table->timestamp('ended_at')->nullable();

            $table->enum('status', ['ongoing', 'completed', 'auto_completed'])->default('ongoing');
            $table->string('remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_breaks');
    }
};
