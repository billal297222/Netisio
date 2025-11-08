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
    Schema::table('kid_transactions', function (Blueprint $table) {
        $table->unsignedBigInteger('receiver_parent_id')->nullable()->after('receiver_kid_id');
        $table->foreign('receiver_parent_id')->references('id')->on('parents')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('kid_transactions', function (Blueprint $table) {
        $table->dropForeign(['receiver_parent_id']);
        $table->dropColumn('receiver_parent_id');
    });
}

};
