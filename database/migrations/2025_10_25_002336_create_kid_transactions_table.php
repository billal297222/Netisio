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
        Schema::create('kid_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kid_id')->nullable()->constrained('kids')->nullOnDelete();
            $table->foreignId('receiver_kid_id')->nullable()->constrained('kids')->nullOnDelete();
            $table->foreignId('sender_parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $table->enum('type', ['send', 'request']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamp('transaction_date')->useCurrent();
            $table->string('note', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kid_transactions');
    }
};
