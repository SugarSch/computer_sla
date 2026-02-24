<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sla_priority extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'response_time_minutes', 'resolve_time_minutes'];

    public function repair_request()
    {
        return $this->hasMany(Repair_request::class, 'sla_track_id', 'id');
    }
}
