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


    public function getMedicalAttachmentPathAttribute()
    {
        $paths = $this->attributes['medical_attachment_path'] ?? null;
        if ( $paths) {
            $paths = json_decode($paths, true);
        }
        if (empty($paths) || !is_array($paths)) {
            return [];
        }
        return array_map(function ($path) {
            return asset('storage/' . $path);
        }, $paths);
    }
}
