<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'repair_statuses')->insert([
            [
                'code' => 'new',
                'label' => 'แจ้งซ้อมใหม',
                'pauses_sla' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'in_progress',
                'label' => 'กำลังดำเนินการ',
                'pauses_sla' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'waiting_user',
                'label' => 'รอข้อมูลเพิ่มเติม',
                'pauses_sla' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'forwarded_first_line',
                'label' => 'ส่งกลับช่างระดับต้น',
                'pauses_sla' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'forwarded_senior',
                'label' => 'ส่งต่อช่างอาวุโส',
                'pauses_sla' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'waiting_approval',
                'label' => 'รออนุมัติอุปกรณ์',
                'pauses_sla' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table(table: 'repair_statuses')->insert([
            [
                'code' => 'completed',
                'label' => 'แก้ไขเสร็จสิ้น',
                'is_final' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'cancelled',
                'label' => 'ยกเลิก',
                'is_final' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
