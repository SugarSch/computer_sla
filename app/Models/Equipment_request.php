<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment_request extends Model
{
    use HasFactory;

    protected $fillable = ['repair_request_id', 'requested_by', 'item_name',
                            'cost', 'status', 'approved_by', 'approved_at'];
    
    protected $casts = ['approved_at' => 'datetime'];

    public function repair_request()
    {
        return $this->belongsTo(Repair_request::class);
    }

    public function requested_user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approved_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
