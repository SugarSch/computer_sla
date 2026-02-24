<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sla_track extends Model
{
    use HasFactory;

    protected $fillable = ['repair_request_id', 'started_at', 'resolved_at'];

    protected $casts = [
        'started_at'  => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function repair_request()
    {
        return $this->belongsTo(Repair_request::class);
    }

    public function sla_pause_log()
    {
        return $this->hasMany(Sla_pause_log::class, 'sla_track_id');
    }

}
