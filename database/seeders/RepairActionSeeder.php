<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Repair_status;
use App\Models\Repair_action_type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RepairActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = array(
            // =====================
            // User
            // =====================
            [
                'code' => 'create_request',
                'label' => 'สร้างคำขอแจ้งซ่อม',
                'from_status' => 0,
                'to_status' => 'new',
                'allowed_roles' => ['user'],
                'affects_sla' => false,
                'active_status' => [null]
            ],
            [
                'code' => 'cancel_request',
                'label' => 'ยกเลิกการแจ้งซ่อม',
                'from_status' => 'new',
                'to_status' => 'cancelled',
                'allowed_roles' => ['user'],
                'affects_sla' => false,
                'active_status' => ['new']
            ],
            [
                'code' => 'view_log',
                'label' => 'ดูประวัติการแจ้งซ่อม',
                'from_status' => 0,
                'to_status' => 0,
                'allowed_roles' => ['user', 'it_manager', 'admin'],
                'affects_sla' => false,
                'active_status' => [null]
            ],

            // =====================
            // First-Line Technician
            // =====================
            [
                'code' => 'accept_job',
                'label' => 'รับเรื่อง',
                'from_status' => 'new',
                'to_status' => 'in_progress',
                'allowed_roles' => ['first_line_technician'],
                'affects_sla' => true, // เริ่ม SLA
                'active_status' => ['new']
            ],
            [
                'code' => 'request_additional_info',
                'label' => 'ขอข้อมูลเพิ่มเติม',
                'from_status' => 'in_progress',
                'to_status' => 'waiting_user',
                'allowed_roles' => ['first_line_technician'],
                'affects_sla' => true, // pause SLA
                'active_status' => ['in_progress']
            ],
            [
                'code' => 'forward_to_senior',
                'label' => 'ส่งต่อช่างอาวุโส',
                'from_status' => 'in_progress',
                'to_status' => 'forwarded_senior',
                'allowed_roles' => ['first_line_technician'],
                'affects_sla' => false,
                'active_status' => ['in_progress']
            ],

            // =====================
            // Senior Technician
            // =====================
            [
                'code' => 'accept_from_first_line',
                'label' => 'รับงานจากช่างระดับต้น',
                'from_status' => 'forwarded_senior',
                'to_status' => 'in_progress',
                'allowed_roles' => ['senior_technician'],
                'affects_sla' => false,
                'active_status' => ['forwarded_senior']
            ],[
                'code' => 'return_to_junior',
                'label' => 'ส่งกลับช่างระดับต้น',
                'from_status' => 'in_progress',
                'to_status' => 'in_progress',
                'allowed_roles' => ['senior_technician'],
                'affects_sla' => false,
                'active_status' => ['forwarded_senior']
            ],
            [
                'code' => 'request_equipment',
                'label' => 'ร้องขออุปกรณ์/อะไหล่',
                'from_status' => 'in_progress',
                'to_status' => 'waiting_approval',
                'allowed_roles' => ['senior_technician'],
                'affects_sla' => true, // pause SLA
                'active_status' => ['in_progress']
            ],
            [
                'code' => 'complete_job',
                'label' => 'แก้ไขเสร็จสิ้น',
                'from_status' => 'in_progress',
                'to_status' => 'completed',
                'allowed_roles' => ['first_line_technician', 'senior_technician'],
                'affects_sla' => true, // stop SLA
                'active_status' => ['in_progress']
            ],

            // =====================
            // IT Manager
            // =====================
            [
                'code' => 'approve_equipment',
                'label' => 'อนุมัติอุปกรณ์',
                'from_status' => 'waiting_approval',
                'to_status' => 'in_progress',
                'allowed_roles' => ['it_manager'],
                'affects_sla' => true, // resume SLA
                'active_status' => ['waiting_approval']
            ],
            [
                'code' => 'reject_equipment',
                'label' => 'ปฏิเสธอุปกรณ์',
                'from_status' => 'waiting_approval',
                'to_status' => 'in_progress',
                'allowed_roles' => ['it_manager'],
                'affects_sla' => true,
                'active_status' => ['waiting_approval']
            ],
            [
                'code' => 'มอบหมายงาน',
                'label' => 'ปฏิเสธอุปกรณ์',
                'from_status' => 'new',
                'to_status' => 'in_progress',
                'allowed_roles' => ['it_manager'],
                'affects_sla' => true,
                'active_status' => ['new', 'in_progress']
            ]
        );
        
        $roles = Role::pluck('id', 'code');
        $repair_status = Repair_status::pluck('id', 'code');

        foreach($rows as $k => $r){
            $from_status = (isset($repair_status[$r['from_status']]) && $repair_status[$r['from_status']]) ? $repair_status[$r['from_status']] : null;
            $to_status = (isset($repair_status[$r['to_status']]) && $repair_status[$r['to_status']]) ? $repair_status[$r['to_status']] : null;
            $allowed_roles = [];

            if(isset($r['allowed_roles']) && $r['allowed_roles']){
                foreach($r['allowed_roles'] as $ar){
                    $allowed_roles[] = $roles[$ar];
                }
            }

            $active_status = [];

            if(isset($r['active_status']) && $r['active_status']){
                foreach($r['active_status'] as $ar){
                    $allowed_roles[] = $roles[$ar];
                }
            }

            $rows[$k]['from_status'] = $from_status;
            $rows[$k]['to_status'] = $to_status;
            $rows[$k]['allowed_roles'] = json_encode($allowed_roles);
            $rows[$k]['active_status'] = json_encode($active_status);
            $rows[$k]['created_at'] = now();
            $rows[$k]['updated_at'] = now();
        }
        

        foreach ($rows as $r) {
            Repair_action_type::updateOrCreate(
                ['code' => $r['code']],
                $r
            );
        }
    }
}
