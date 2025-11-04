<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update the enum to add 'collected'
        Schema::table('saving_goals', function (Blueprint $table) {
            $table->enum('status', ['in_progress', 'completed', 'collected'])
                  ->default('in_progress')
                  ->change();
        });
    }

    public function down()
    {
        // Rollback: remove 'collected'
        Schema::table('saving_goals', function (Blueprint $table) {
            $table->enum('status', ['in_progress', 'completed'])
                  ->default('in_progress')
                  ->change();
        });
    }
};
