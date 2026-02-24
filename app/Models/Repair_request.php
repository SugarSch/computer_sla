<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair_request extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id', 'repair_status_id', 'sla_priority_id', 'assigned_to'];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigned_to_user(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function repair_status(){
        return $this->hasOne(Repair_status::class, 'id','repair_status_id');
    }

    public function sla_priority(){
        return $this->hasOne(Sla_priority::class, 'id', 'sla_priority_id');
    }
}
