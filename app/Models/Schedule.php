<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'work_date',
        'start_time',
        'end_time',
        'status',
        'type',
        'remark',
    ];

    protected $casts = [
        'staff_id' => 'integer',
        'work_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
