<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the orders table');
            $table->decimal('amount', 10, 2)
                ->comment('Amount paid for the order');
            $table->string('payment_method')
                ->comment('Method of payment used for the order, e.g., pos, cash, voucher');
            $table->string('status')
                ->comment('Payment status (e.g., success, failed)');
            $table->decimal('tax_rate', 5, 2)->default(10.00)
            ->comment('Tax rate applied to the order (e.g., GST)');
            $table->decimal('tax_amount', 10, 2)->default(0.00)
            ->comment('Tax amount applied to the order (e.g., GST)');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
