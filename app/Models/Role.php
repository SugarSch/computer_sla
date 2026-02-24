<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'label', 'description'];

    protected $casts = [
        'view' => 'array',
    ];

    public function permission()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions'
        );
    }

    // public function view(){

    // }
}
