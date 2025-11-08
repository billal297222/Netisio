<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('add_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kid_id');
            $table->enum('member_type', ['kid', 'parent']);
            $table->string('member_unique_id')->unique();
            $table->string('member_avatar')->nullable();
            $table->timestamps();

            $table->foreign('kid_id')->references('id')->on('kids')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('add_list');
    }
};
