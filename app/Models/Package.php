<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

}
