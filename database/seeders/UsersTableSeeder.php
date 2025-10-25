<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'status' => 'active',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'avatar' => 'default.png',
            'admin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@user.com',
            'status' => 'active',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'avatar' => 'default.png',
            'admin' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
