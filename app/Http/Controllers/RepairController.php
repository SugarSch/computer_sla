<?php

namespace App\Http\Controllers;

use App\Models\Sla_priority;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Services\SlaService;

use App\Models\User;
use App\Models\Sla_track;
use App\Models\Attachment;
use App\Models\Repair_status;
use App\Models\Sla_pause_log;
use App\Models\Repair_request;
use App\Models\Repair_log;
use App\Models\Equipment_request;
use App\Models\Repair_action_type;

class RepairController extends Controller
{
    private function repairViewList() {
        
        $user = Auth::user();
        $roleView = $user->role->view['repair'] ?? 'own';
        $repair_requests = [];
        $query = Repair_request::with([
            'user',
            'repair_status',
            'sla_priority',
            'assigned_to_user'
        ]);
        switch ($roleView) {
            case 'all':
                $repair_requests = $query;
                break;
            case 'assigned':
                //ของช่างระดับสูง
                $repair_requests = $query->where('assigned_to', $user->id);
                break;
            case 'unassigned':
                //ของช่างระดับต้น
                $repair_requests = $query->where('assigned_to', $user->id)
                                    ->orWhere('assigned_to',null);
                break;
            case 'own':
                $repair_requests = $query->where('user_id', $user->id);
                break;
            case 'own_department':
                $repair_requests = $query->where(function ($qq) use ($user) {
                    $qq->whereHas('user', fn ($q) => $q->where('id', $user->id))
                    ->orWhereHas('user', fn ($q) => $q->where('department_id', $user->department_id));
                });

                break;
        }

        return $repair_requests;
    }

    private function repairViewStatus() {
        $user = Auth::user();
        $repair_status = $user->role->view['repair_status'] ?? 'own';
        if($repair_status == 'own'){
            $can_see_status = Repair_request::where("user_id", $user->id);
        }else{
            $can_see_status = Repair_request::all();
        }

        return $can_see_status->pluck('id')->toArray();
    }
    public function dashboard() {
        $user = Auth::user();

        $repairs = $this->repairViewList();
        
        $add_new_repair = $user->hasPermission('repair.create') ? true : false;

        $can_see_status = $this->repairViewStatus();

        return Inertia::render('Dashboard', [
            'repairs' => $repairs->orderBy('created_at', 'desc')->paginate(5)->withQueryString(), 
            'add_new_repair' => $add_new_repair, 'can_see_status' => $can_see_status
        ]);
    }

    private function uploadAttachment($repair, $files){
        if($repair && $files){
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();

                // save to storage/app/public/repair_uploads
                $path = $file->storeAs(
                    'repair_uploads',
                    $fileName,
                    'public'
                );

                Attachment::create([
                    'file_name' => $fileName,
                    'repair_request_id' => $repair->id,
                    'uploaded_by' => Auth::user()->id,
                    'file_type' => $file->getClientOriginalExtension()
                ]);
            }
        }
    }

    public function attachmentRemove(Attachment $attachment){
        if(Auth::user()->id == $attachment->user->id){
            if (Storage::disk('public')->exists('repair_uploads/'.$attachment->file_name)) {
                Storage::disk('public')->delete('repair_uploads/'.$attachment->file_name);
            }

            // delete database record
            $attachment->delete();

            return back()->with('success','ลบไฟล์สำเร็จ');
        }else{
            return back()->with('error','คุณไม่มิสิทธิลบไฟล์นี้');
        }
    }

    public function repairCreateForm() {
        if(!Auth::user()->hasPermission('repair.create')){
            return back()->with('error','คุณไม่มีสิทธิในการสร้างใบแจ้งซ่อม');
        }

        $sla_priority = Sla_priority::all();

        return Inertia::render('Repair/Form', ['action' => 'add', 'sla_priority' => $sla_priority, 'repair' => []]);
    }

    public function repairCreate(Request $request) {

        //ถ้าไม่มีสิทธิสร้างใบแจ้งจะ redierect
        if(!Auth::user()->hasPermission('repair.create')){
            return back()->with('error','คุณไม่มีสิทธิในการสร้างใบแจ้งซ่อม');
        }
        
        $request->validate([
            'title' => ['required','string', 'max:255'],
            'description' => ['required','string'],
            'attachments' => ['array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx','max:3072']
        ]);

        $row = $request->except('attachments');

        $row['user_id'] = Auth::user()->id;
        $row['repair_status_id'] = Repair_status::where('code', 'new')->value('id');

        $repair = Repair_request::create($row);

        //เก็บ log ว่าสร้างใบแจ้งซ่อมใหม่
        Repair_log::create(['repair_request_id' => $repair->id, 
                                        'user_id' => Auth::user()->id, 
                                        'repair_action_type_id' => $repair->repair_status_id,
                                        'message' => 'ยื่นเรื่องแจ้งซ่อมใหม่']); 

        //upload ไฟล์
        $this->uploadAttachment($repair, $request->file('attachments'));

        return redirect()->route('repair-detail', ['repair' => $repair->id])->with('success','แจ้งซ่อมสำเร็จ');
    }

    public function repairDetail(Repair_request $repair) {

        //eager loading ข้อมูลจาก table อื่น เพื่อเอามาแสดง
        $repair->load([
            'user',
            'repair_status',
            'sla_priority',
            'assigned_to_user',
        ]);

        //ดึงข้อมูลไฟล์แนบ
        $attachments = Attachment::where('repair_request_id', $repair->id)->get();

        $can_see_status_list = $this->repairViewStatus();
        $can_see_status = false;
        if(in_array($repair->id,$can_see_status_list)){
            $can_see_status = true;
        }

        $repair_action_type = $this->repairActionList($repair);

        // คำนวณ SLA
        $slaService = new SlaService();
        $slaStatus = $slaService->getSlaStatus($repair);

        return Inertia::render('Repair/Detail', 
                                ['can_see_status' => $can_see_status, 
                                'repair' => $repair, 'attachments' => $attachments,
                                'user_id' => Auth::user()->id, 
                                'repair_action_type' => $repair_action_type,
                                'slaStatus' => $slaStatus]);
    }

    private function repairActionList($repair) {
        $repair_action_type = [];
        if($repair->repair_status->code != 'completed' && $repair->repair_status->code != 'cancelled'){
            $repair_action_type = Repair_action_type::whereJsonContains('allowed_roles', (int) Auth::user()->role_id)
                                                    ->where(function ($q) use ($repair) {
                                                            $q->whereJsonContains('active_status', (int) $repair->repair_status->id)
                                                            ->orWhereNull('to_status');
                                                        })
                                                    ->where('code','!=','reject_equipment')
                                                    ->get();
        }else{
            $repair_action_type = Repair_action_type::whereJsonContains('allowed_roles', (int) Auth::user()->role_id)
            ->whereNull('to_status')->where('code','!=','reject_equipment')->get();
        }

        return $repair_action_type;
    }
    public function log(Repair_request $repair){

        //ดึงข้อมูลจาก DB
        $logs = Repair_log::where('repair_request_id', $repair->id)
            ->with(['user', 'repair_action_type'])
            ->orderBy('created_at', 'desc')
            ->get();

        //จัดข้อมูล
        $timeline = $logs->map(function($log) {
            return [
                'id' => $log->id,
                'user' => $log->user->username,
                'action' => $log->repair_action_type->label, // เช่น "รับเรื่อง", "ส่งต่อช่างอาวุโส"
                'message' => $log->message,
                'timestamp' => $log->created_at,
                'type' => 'status_change'
            ];
        });

        return Inertia::render('Repair/Log', [
            'repair' => $repair,
            'timeline' => $timeline
        ]);
    }

    public function assignForm(Repair_request $repair){
        $user = Auth::user();
        $target_role_codes = [];
        $title = "มอบหมายงาน";

        // ใครเห็นช่างคนไหนได้
        if ($user->role->code == 'first_line_technician') {
            // ช่างต้น -> ส่งต่อช่างอาวุโส
            $target_role_codes = ['senior_technician'];
            $title = "ส่งต่อช่างอาวุโส";
        } elseif ($user->role->code == 'senior_technician') {
            // ช่างอาวุโส -> ส่งกลับช่างต้น
            $target_role_codes = ['first_line_technician'];
            $title = "ส่งกลับช่างระดับต้น";
        } elseif ($user->role->code == 'it_manager' || $user->role->code == 'admin') {
            // Manager -> มอบหมายให้ใครก็ได้
            $target_role_codes = ['first_line_technician', 'senior_technician'];
            $title = "มอบหมายงาน";
        }

        // Query User ตาม Role ที่กำหนด
        $technicians = User::whereHas('role', function($q) use ($target_role_codes){
            $q->whereIn('code', $target_role_codes);
        })->get();

        return Inertia::render('Repair/Assign', [
            'repair' => $repair,
            'technicians' => $technicians,
            'page_title' => $title
        ]);
    }

    public function assignSubmit(Request $request, Repair_request $repair) {
        $request->validate(['assigned_to' => ['required','exists:users,id']]);
        
        $repair->assigned_to = $request->assigned_to;
        
        // อัปเดตสถานะตาม Flow
        $user = Auth::user();
        $status_code = 'in_progress';

        if ($user->role->code == 'first_line_technician') {
            $status_code = 'forwarded_senior';
        } elseif ($user->role->code == 'senior_technician' || $user->role->code == 'it_manager') {
            $status_code = 'in_progress';
            Sla_track::firstOrCreate(
                ['repair_request_id' => $repair->id],
                ['started_at' => now()]
            );
        }

        $status = Repair_status::where('code', $status_code)->first();
        if($status) {
            $repair->repair_status_id = $status->id;
        }

        $repair->save();

        Repair_log::create(['repair_request_id' => $repair->id, 
                                        'user_id' => Auth::user()->id, 
                                        'repair_action_type_id' => $repair->repair_status_id,
                                        'message' => 'ส่งงานให้ช่าง '.$repair->assigned_to_user->username]); 
        if($status_code!= 'in_progress'){
            // Trigger SLA Pause
            $track = Sla_track::where('repair_request_id', $repair->id)->first();
            if ($track) {
                Sla_pause_log::create([
                    'sla_track_id' => $track->id,
                    'reason' => 'รอช่างรับเรื่อง', // เช่น "รอข้อมูลเพิ่มเติม"
                    'paused_at' => now()
                ]);
            }
        }
        
        return redirect()->route('repair-detail', $repair->id)->with('success', 'บันทึกการมอบหมายงานสำเร็จ');
    }

    public function repairAction(Repair_request $repair, $repair_action_type) {
        $action_type = Repair_action_type::where('code', $repair_action_type)->first();
        $user = Auth::user();

        // Handle Special Redirect Actions
        if ($action_type->code == 'view_log') {
            return redirect()->route('repair-log', ['repair' => $repair->id]);
        }else if (in_array($action_type->code, ['forward_to_senior', 'assign_to', 'return_to_junior'])) {
            // Redirect ไปเลือกคน
            return redirect()->route('repair-assign-form', ['repair' => $repair->id, 'role' => $action_type->code]); 
        }else if ($action_type->code == 'approve_equipment') {
            return redirect()->route('equipment-approve-form', ['repair' => $repair->id]);
        }
        
        // State Transition Logic & SLA Management
        $currentStatus = $repair->repair_status->code;
        $nextStatusObj = $action_type->toStatus;
        $nextStatus = $nextStatusObj->code;

        // Start SLA (เมื่อรับเรื่อง)
        if ($currentStatus == 'new' && $nextStatus == 'in_progress') {
            $repair->assigned_to = $user->id; // Assign ให้ตัวเอง
            
            // สร้าง SLA Track
            Sla_track::firstOrCreate(
                ['repair_request_id' => $repair->id],
                ['started_at' => now()]
            );
        }

        // Pause SLA (รอข้อมูล / รออะไหล่)
        if (!$repair->repair_status->pauses_sla && $nextStatusObj->pauses_sla) {
            $track = Sla_track::where('repair_request_id', $repair->id)->first();
            if ($track) {
                Sla_pause_log::create([
                    'sla_track_id' => $track->id,
                    'reason' => $action_type->label, // เช่น "รอข้อมูลเพิ่มเติม"
                    'paused_at' => now()
                ]);
            }
        }

        // Resume SLA (กลับมาดำเนินการ)
        if ($repair->repair_status->pauses_sla && !$nextStatusObj->pauses_sla) {
            $track = Sla_track::where('repair_request_id', $repair->id)->first();
            if ($track) {
                // หา log ล่าสุดที่ยังไม่ resume
                $pauseLog = Sla_pause_log::where('sla_track_id', $track->id)
                            ->whereNull('resumed_at')
                            ->latest()->first();
                if ($pauseLog) {
                    $pauseLog->update(['resumed_at' => now()]);
                }
            }
        }

        // Complete Job (หยุด SLA)
        if ($nextStatus == 'completed') {
            $track = Sla_track::where('repair_request_id', $repair->id)->first();
            if ($track) {
                $track->update(['resolved_at' => now()]);
            }
        }
        

        // Update Status Final
        $repair->repair_status_id = $nextStatusObj->id;
        $repair->save();

        // เก็บประวัติการเปลี่ยนสถานะ
        Repair_log::create(['repair_request_id' => $repair->id, 
                                        'user_id' => Auth::user()->id, 
                                        'repair_action_type_id' => $repair->repair_status_id,
                                        'message' => 'อัพเดทสถานะเป็น "'.$nextStatusObj->label.'"']); 

        // Handle Equipment Request (กรณีช่างอาวุโสขออะไหล่) 
        if ($action_type->code == 'request_equipment') {
            // Redirect ไปหน้าฟอร์มขออุปกรณ์
            return redirect()->route('equipment-request-form', ['repair' => $repair->id]);
        }

        return redirect()->route('repair-detail', ['repair'=> $repair->id])->with('success', 'ดำเนินการสำเร็จ');
    }

    public function equipmentForm(Repair_request $repair) {
        return Inertia::render('Repair/Equipment', ['repair' => $repair]);
    }
    public function equipmentSubmit(Request $request, Repair_request $repair) {
        // Validate
        $request->validate(['item_name' => 'required', 'cost' => 'required|numeric']);
        
        // Create Request [cite: 317-326]
        Equipment_request::create([
            'repair_request_id' => $repair->id,
            'requested_by' => Auth::id(),
            'item_name' => $request->item_name,
            'cost' => $request->cost,
            'status' => 'pending'
        ]);

        // Update Status -> Waiting Approval
        $status = Repair_status::where('code', 'waiting_approval')->first();
        $repair->update(['repair_status_id' => $status->id]);
        
        // Trigger SLA Pause
        $track = Sla_track::where('repair_request_id', $repair->id)->first();
        if ($track) {
            Sla_pause_log::create([
                'sla_track_id' => $track->id,
                'reason' => 'รออนุมัติอุปกรณ์', // เช่น "รอข้อมูลเพิ่มเติม"
                'paused_at' => now()
            ]);
        }
        

        return redirect()->route('repair-detail', $repair->id);
    }

    public function equipmentApproveForm(Repair_request $repair) {
        $equipmentItems = Equipment_request::where('repair_request_id', $repair->id)
        ->with('requested_user')
        ->get();

        return Inertia::render('Repair/EquipmentApproveForm', [
            'repair' => $repair,
            'equipmentItems' => $equipmentItems
        ]);
    }

    public function equipmentApprove(Request $request, Equipment_request $equipment_request) {
        $request->validate(['status' => ['required']]);
        
        // อัปเดตสถานะอุปกรณ์
        $equipment_request->update([
            'status' => $request->status,
            'approved_by' => Auth::id() // ถ้าในตารางมีฟิลด์นี้
        ]);

        // บันทึกลง Log ของระบบ
        Repair_log::create([
            'repair_request_id' => $equipment_request->repair_request_id,
            'user_id' => Auth::id(),
            'repair_action_type_id' => Repair_action_type::where('code', 'approve_equipment')->first()->id ?? 1,
            'message' => ($request->status == 'approved' ? 'อนุมัติ' : 'ปฏิเสธ') . "อุปกรณ์: " . $equipment_request->item_name
        ]);

        // เช็คว่าถ้าอนุมัติครบหรือยกเลิกทุกชิ้นแล้ว ให้เปลี่ยนสถานะใบแจ้งซ่อมกลับเป็น 'In Progress'
        if (!Equipment_request::where('repair_request_id', $equipment_request->repair_request_id)
                    ->where('status', 'pending')
                    ->exists()
            ) {
                $repair = Repair_request::find($equipment_request->repair_request_id);

                if ($repair) {
                    $repair->update([
                        'repair_status_id' => Repair_status::where('code', 'in_progress')->value('id')
                    ]);
                }
            }


        return back()->with('success', 'ดำเนินการเรียบร้อยแล้ว');
    }

}
