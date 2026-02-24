<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair_action_type extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'label', 'from_status', 'to_status', 'allowed_roles', 'affects_sla', 'is_active'];

    protected $casts = [
        'allowed_roles' => 'array',
    ];

    public function fromStatus()
    {
        return $this->belongsTo(Repair_status::class, 'from_status');
    }

    public function toStatus()
    {
        return $this->belongsTo(Repair_status::class, 'to_status');
    }
}
