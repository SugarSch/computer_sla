<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'departments')->insert([
            [
                'name' => 'การประปา',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ไฟฟ้า',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'บริหาร',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IT',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
