<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens ,SoftDeletes;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'first_name',
        'last_name',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

      protected $appends = ['role'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function getRoleAttribute()
    {
        // If the user has a role, return it; otherwise, check if the user is a staff member
        // and return the staff role if available.
        if ($this->specialRoles->isNotEmpty()) {
            return $this->specialRoles->first()->name;
        } elseif ($this->staff) {
            return "Staff" ?? null;
        }
        return null;
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function specialRoles()
    {
        return $this->hasMany(SpecialRole::class);
    }
}
