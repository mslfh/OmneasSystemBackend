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
                ->comment('created, pending_payment, paid, cancelled, refunded,failed');

            $table->string('payment_status')->default('pending')
                ->comment('pending, success, failed, refunded');

            $table->string('payment_method')->default('credit_card')
                ->comment('cash, credit_card, bank_transfer, voucher, split_payment,unpaid');

            $table->double('total_amount')->default(0);
            $table->double('paid_amount')->default(0);

            $table->integer('used_id')->nullable();

            $table->integer('confirmed_by')->nullable()->comment(
                'id of the staff who confirmed the order'
            );
            $table->string('confirmed_staff_name')->nullable()->comment(
                'name of the staff who confirmed the order'
            );
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
