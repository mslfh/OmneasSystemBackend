<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'port',
        'type',
        'status',
        'description',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function printLogs()
    {
        return $this->hasMany(PrintLog::class);
    }

    public function printTemplates()
    {
        return $this->hasMany(PrintTemplate::class);
    }
}
