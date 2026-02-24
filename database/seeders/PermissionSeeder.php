<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [

            // User
            ['code' => 'repair.create', 'label' => 'สร้างใบแจ้งซ่อม'],
            ['code' => 'repair.cancel', 'label' => 'ยกเลิกใบแจ้งซ่อม'],
            ['code' => 'repair.upload', 'label' => 'อัปโหลดไฟล์เพิ่มเติม'],

            // First-Line Technician
            ['code' => 'repair.accept', 'label' => 'รับเรื่องแจ้งซ่อม'],
            ['code' => 'repair.update', 'label' => 'บันทึกการดำเนินการ'],
            ['code' => 'repair.forward', 'label' => 'ส่งต่อช่างอาวุโส'],

            // Senior Technician
            ['code' => 'repair.return', 'label' => 'ส่งกลับช่างระดับต้น'],
            ['code' => 'repair.request_equipment', 'label' => 'ร้องขออุปกรณ์'],

            // IT Manager
            ['code' => 'equipment.approve', 'label' => 'อนุมัติอุปกรณ์'],
            ['code' => 'equipment.reject', 'label' => 'ปฏิเสธอุปกรณ์'],
            ['code' => 'repair.assign', 'label' => 'มอบหมายงาน'],
            ['code' => 'repair.set_priority', 'label' => 'กำหนดความเร่งด่วน'],

            // Admin
            ['code' => 'system.manage', 'label' => 'จัดการระบบ'],
        ];

        foreach ($rows as $r) {
            Permission::updateOrCreate(
                ['code' => $r['code']],
                $r
            );
        }
    }
}
