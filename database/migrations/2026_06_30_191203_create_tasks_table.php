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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Creator / Manager
            $table
                ->foreignId('assigned_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Engineer
            $table
                ->foreignId('assigned_to')
                ->constrained('users')
                ->cascadeOnDelete();

            // Tester (optional)
            $table
                ->foreignId('tester_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('project_name')->nullable();

            $table->string('title');
            $table->longText('description');

            $table->date('deadline')->nullable();

            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'critical'
            ])->default('medium');

            $table->enum('status', [
                'open',
                'in_progress',
                'ready_for_test',
                'testing',
                'failed_testing',
                'completed',
                'closed'
            ])->default('open');

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
