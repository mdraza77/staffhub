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
        Schema::create('defects', function (Blueprint $table) {
            $table->id();
            $table->string('defect_id')->unique();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('steps_to_reproduce')->nullable();

            $table->string('module')->nullable();
            $table->string('sub_module')->nullable();
            $table->string('environment')->default('production')->nullable();
            $table->string('browser_os')->nullable();

            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->nullable();
            $table->enum('status', ['open', 'in_progress', 'ready_for_testing', 'closed', 'reopened'])->default('open')->nullable();
            $table->dateTime('deadline')->nullable();

            $table->foreignId('reported_by')->constrained('users')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('closed_by')->nullable()->constrained('users');

            $table->dateTime('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defects');
    }
};
