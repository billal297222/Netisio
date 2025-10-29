<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backend', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_limit', 10, 2)->default(10000.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backend');
    }
};
