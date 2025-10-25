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
        Schema::create('parent_transactions', function (Blueprint $table) {
             $table->id();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('kid_id')->nullable()->constrained('kids')->nullOnDelete();
            $table->enum('type', ['deposit', 'transfer']);
            $table->decimal('amount', 10, 2);
            $table->decimal('max_deposit', 10, 2)->default(1000.00);
            $table->string('message', 255)->nullable();
            $table->timestamp('transaction_datetime')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_transactions');
    }
};
