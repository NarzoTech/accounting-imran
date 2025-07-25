<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // EmailTemplateSeeder::class,

            RolePermissionSeeder::class,
            AdminInfoSeeder::class,
            GlobalSettingInfoSeeder::class,


        ]);
    }
}
