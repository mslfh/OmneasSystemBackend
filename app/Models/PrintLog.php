<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_template_id',
        'order_id',
        'status',
        'printed_content',
        'result',
        'printer_name',
    ];

    protected $casts = [
        'print_template_id' => 'integer',
        'order_id' => 'integer',
    ];

    public function printTemplate()
    {
        return $this->belongsTo(PrintTemplate::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class, 'printer_name', 'name');
    }
}
