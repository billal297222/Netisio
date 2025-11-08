<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('add_list', function (Blueprint $table) {
            $table->string('member_name')->nullable()->after('member_unique_id');
        });
    }

    public function down(): void
    {
        Schema::table('add_list', function (Blueprint $table) {
            $table->dropColumn('member_name');
        });
    }
};
