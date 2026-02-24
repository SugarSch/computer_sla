<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'code' => 'admin',
                'label' => 'แอดมิน',
                'description' => 'ผู้ดูแลระบบทั้งหมด',
                'view' => json_encode(['repair' => 'all', "repair_status"=> "all", 'repair_log' => 'all']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'it_manager',
                'label' => 'ผู้จัดการฝ่าย IT',
                'description' => 'ดูแลและบริหารจัดการฝ่าย IT',
                'view' => json_encode(['repair' => 'all', "repair_status"=> "all", 'repair_log' => 'all']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'senior_technician',
                'label' => 'ช่างเทคนิคอาวุโส',
                'description' => 'ช่างเทคนิคระดับอาวุโส',
                'view' => json_encode(['repair' => 'assigned', "repair_status"=> "all", 'repair_log' => 'all']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'first_line_technician',
                'label' => 'ช่างเทคนิคระดับต้น',
                'description' => 'ช่างเทคนิคด่านแรก',
                'view' => json_encode(['repair' => 'unassigned', "repair_status"=> "all",'repair_log' => 'first_line_technician']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'user',
                'label' => 'ผู้ใช้งานทั่วไป',
                'description' => 'ผู้ใช้งานระบบทั่วไป',
                'view' => json_encode(['repair' => 'own_department', "repair_status"=> "own", 'repair_log' => 'none']),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
