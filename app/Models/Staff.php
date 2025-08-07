<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['email','phone'];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function getEmailAttribute()
    {
        $user = $this->user()->first();
        return  $user->email?? null;
    }

    public function getPhoneAttribute()
    {
        $user = $this->user()->first();
        return  $user->phone?? null;
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
