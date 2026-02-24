<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['repair_request_id', 'uploaded_by', 'file_name', 'file_type'];

    protected $appends = ['file_path'];

    protected function filePath(): Attribute //use file_path in react
    {
        return Attribute::get(function ($value, $attributes) {
            return '/storage/repair_uploads/' . $attributes['file_name'];
        });
    }

    public function repair_request(){
        return $this->belongsTo(Repair_request::class, 'repair_request_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
