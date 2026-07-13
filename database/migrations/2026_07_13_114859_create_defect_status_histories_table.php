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
        Schema::create('defect_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defect_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->constrained('users');

            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_status_histories');
    }
};
