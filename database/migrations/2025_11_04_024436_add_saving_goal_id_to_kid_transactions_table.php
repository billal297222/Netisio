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
        Schema::table('kid_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('saving_goal_id')->nullable()->after('sender_parent_id');
            $table->foreign('saving_goal_id')->references('id')->on('saving_goals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kid_transactions', function (Blueprint $table) {
             $table->dropForeign(['saving_goal_id']);
            $table->dropColumn('saving_goal_id');
        });
    }
};
