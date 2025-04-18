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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments');

            $table->string('order_status')->default('pending')
                ->comment('pending, completed, paid, cancelled'); // Fixed missing semicolon

            $table->string('payment_status')->default('pending')
                ->comment('pending, success, failed, refunded'); // Fixed missing semicolon

            $table->string('payment_method')->default('credit_card')
                ->comment('cash, credit_card, bank_transfer, voucher, other'); // Fixed missing semicolon

            $table->double('total_amount')->default(0);
            $table->double('paid_amount')->default(0);

            $table->foreignId('operator_id')->constrained('staff')->nullable();
            $table->string('operator_name')->nullable();
            $table->string('payment_note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
