<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'no',
        'appointment_id',
        'type',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'subject',
        'content',
        'status',
        'schedule_time',
        'error_message',
        'remark',
    ];
}
