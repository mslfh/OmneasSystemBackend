<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->comment('Name of the printer');
            $table->string('ip_address')
                ->comment('IP address of the printer');
            $table->string('port')
                ->default('9100')
                ->comment('Port number for the printer, default is 9100');
            $table->string('type')
                ->default('network')
                ->comment('Type of the printer, e.g., network, bluetooth, etc.');
            $table->string('status')
                ->default('offline')
                ->comment('Current status of the printer, e.g., online, offline, error');
            $table->text('description')
                ->nullable()
                ->comment('Optional description of the printer');
            $table->boolean('is_default')
                ->default(false)
                ->comment('Indicates if this printer is the default printer');
            $table->boolean('is_active')
                ->default(true)
                ->comment('Indicates if the printer is currently active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
