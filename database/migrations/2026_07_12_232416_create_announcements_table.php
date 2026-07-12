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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('message');

            $table
                ->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('publish_date')->nullable();

            $table->enum('priority', [
                'low',
                'medium',
                'high'
            ])->default('medium');

            $table->enum('status', [
                'published',
                'draft'
            ])->default('draft');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
