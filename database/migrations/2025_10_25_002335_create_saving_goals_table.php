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
        Schema::create('saving_goals', function (Blueprint $table) {
           $table->id();
            $table->foreignId('kid_id')->constrained('kids')->cascadeOnDelete();
            $table->string('title', 150);
            $table->decimal('target_amount', 10, 2);
            $table->decimal('saved_amount', 10, 2)->default(0.00);
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->foreignId('created_by_parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_goals');
    }
};
