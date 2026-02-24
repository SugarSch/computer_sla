<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'label',
        'description'
    ];

    public function role()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions'
        );
    }
}
