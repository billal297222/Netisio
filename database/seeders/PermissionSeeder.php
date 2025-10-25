<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard_stats',
            'cms_category',
            'cms_faq',
            'cms_pages',
            'setting_profile',
            'setting_admin',
            'setting_system',
            'setting_mail',
            'role_management',
            'user_management',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission], 
                [
                    'guard_name' => 'web', 
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }

    
}
