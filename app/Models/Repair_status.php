<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repair_status extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'label', 'pauses_sla', 'is_final', 'is_active', 'sort_order'];

    protected $casts = [
        'pauses_sla' => 'boolean',
        'is_active' => 'boolean',
        'is_final' => 'boolean'];
}
