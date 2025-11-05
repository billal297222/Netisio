<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kid_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kid_id')->constrained('kids')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kid_notifications');
    }
};
