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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Relation: Who assigned the task (Manager)
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();

            // Relation: To Whom the task is assigned (Employee)
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();

            // Core Task Details
            $table->string('project_name')->nullable();
            $table->string('title');
            $table->text('description'); // Detailed instructions
            $table->date('deadline');

            // Tracking
            $table->integer('progress')->default(0); // Percentage: 0 se 100
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');

            // External Links (Google Drive, GitHub, etc.)
            $table->text('media_links')->nullable();

            // Remarks
            $table->text('manager_remark')->nullable();
            $table->text('employee_remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
