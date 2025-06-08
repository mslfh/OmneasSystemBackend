<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'medical_attachment_path' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getMedicalAttachmentPathAttribute($value)
    {
        $paths = is_string($value) ? json_decode($value, true) : $value;
        if (empty($paths) || !is_array($paths)) {
            return [];
        }
        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $paths);
    }
}
