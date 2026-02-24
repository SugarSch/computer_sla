<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        //กำหนดให้ seeder ทำตามลำดับ
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            RepairSeeder::class,
            RepairActionSeeder::class,
            SlaSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminSeeder::class
        ]);
    }
}
