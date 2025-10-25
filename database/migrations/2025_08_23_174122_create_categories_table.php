<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Auto increment ID
            $table->string('name'); // Category name
            $table->string('slug')->unique(); // Slug for SEO
            $table->integer('priority')->default(0); // Sorting order
            $table->string('image')->nullable(); // Image path
            $table->boolean('status')->default(1); // 1=active, 0=inactive
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at (for soft deletes)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
