<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sla_pause_log extends Model
{
    use HasFactory;

    protected $fillable = ['sla_track_id', 'reason', 'paused_at', 'resumed_at'];

    protected $casts = [
        'paused_at'  => 'datetime',
        'resumed_at' => 'datetime',
    ];

    public function sla_track(){
        return $this->belongsTo(Sla_track::class);
    }
}
