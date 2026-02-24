<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair_log extends Model
{
    use HasFactory;
    protected $fillable = ['repair_request_id', 'user_id', 'repair_action_type_id', 'message'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repair_action_type(){
        return $this->belongsTo(Repair_action_type::class, 'repair_action_type_id');
    }
}
