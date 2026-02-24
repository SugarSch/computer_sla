<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Repair_request;
use App\Models\Sla_track;
use App\Models\Sla_pause_log;
use App\Models\Sla_priority;

class SlaService
{
    const START_HOUR = 9;
    const END_HOUR = 17;

    /**
     * คำนวณ Deadline โดยนับเฉพาะ Business Hours
     */
    public function calculateDeadline(Carbon $startDate, int $minutesAllowed)
    {
        $current = $startDate->copy();
        $minutesAdded = 0;

        while ($minutesAdded < $minutesAllowed) {
            // ข้ามวันหยุดเสาร์อาทิตย์
            if ($current->isWeekend()) {
                $current->addDay()->startOfDay()->setHour(self::START_HOUR);
                continue;
            }

            // ข้ามเวลานอกทำการ (หลัง 17:00 หรือ ก่อน 09:00)
            if ($current->hour >= self::END_HOUR) {
                $current->addDay()->startOfDay()->setHour(self::START_HOUR);
                continue;
            }
            if ($current->hour < self::START_HOUR) {
                $current->setHour(self::START_HOUR)->setMinute(0);
                continue;
            }

            // เพิ่มเวลาทีละนาที
            $current->addMinute();
            $minutesAdded++;
        }

        return $current;
    }

    /**
     * คำนวณเวลาที่ใช้ไปจริงหักลบเวลาที่ Pause
     */
    public function getSlaStatus(Repair_request $repair)
    {
        $track = Sla_track::where('repair_request_id', $repair->id)->first();
        if (!$track) return ['status' => 'pending', 'color' => 'secondary', 'text' => 'ยังไม่เริ่มดำเนินการ'];

        $priority = $repair->sla_priority;
        $allowedMinutes = $priority->resolve_time_minutes;
        
        $startedAt = $track->started_at;
        $deadline = $this->calculateDeadline($startedAt, $allowedMinutes);

        // คำนวณเวลาที่ Pause ไปทั้งหมด
        $totalPauseMinutes = 0;
        foreach ($track->sla_pause_log as $log) {
            $pauseStart = $log->paused_at;
            $pauseEnd = $log->resumed_at ?? now(); // ถ้ายังไม่ resume ให้คิดถึงปัจจุบัน
            $totalPauseMinutes += $pauseStart->diffInMinutes($pauseEnd);
        }

        // ขยับ Deadline ออกไปตามเวลาที่ Pause
        $deadline->addMinutes($totalPauseMinutes);

        $now = now();
        $isResolved = $repair->repair_status->is_final;
        
        if ($isResolved && $track->resolved_at) {
             $now = $track->resolved_at;
        }

        $minutesRemaining = $now->diffInMinutes($deadline, false); // false = return negative if passed

        // คำนวณ % เพื่อกำหนดสี
        $percentLeft = ($minutesRemaining / $allowedMinutes) * 100;

        if ($minutesRemaining < 0) {
            return ['status' => 'overdue', 'color' => 'danger', 'text' => 'เกินกำหนดเวลา ' . $deadline->diffForHumans($now)];
        } elseif ($percentLeft <= 25) {
            return ['status' => 'critical', 'color' => 'warning', 'text' => 'วิกฤต (เหลือ ' . $deadline->diffForHumans($now) . ')']; // ส้ม
        } elseif ($percentLeft <= 50) {
            return ['status' => 'warning', 'color' => 'warning', 'text' => 'ใกล้หมดเวลา (เหลือ ' . $deadline->diffForHumans($now) . ')']; // เหลือง
        } else {
            return ['status' => 'good', 'color' => 'success', 'text' => 'อยู่ในเวลา (เหลือ ' . $deadline->diffForHumans($now) . ')']; // เขียว
        }
    }
}