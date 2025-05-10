<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

}
