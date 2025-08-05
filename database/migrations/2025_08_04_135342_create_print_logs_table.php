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
        Schema::create('print_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_template_id')
                ->constrained('print_templates')
                ->onDelete('cascade');
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');
            $table->string('status')->default('pending')
                ->comment('Status of the print log, e.g., pending, success, failed');
            $table->string('printed_content')
                ->comment('Content that was printed, if applicable');
            $table->string('result')
                ->comment('Result of the print operation, e.g., success message, error message');
            $table->string('printer_name')
                ->comment('Name of the printer used for the print operation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_logs');
    }
};
