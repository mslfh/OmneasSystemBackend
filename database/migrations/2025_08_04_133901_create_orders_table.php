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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()
                ->comment('Automatically created unique identifier for the order');
            $table->integer('user_id')->nullable()
                ->comment('ID of the user who placed the order, null if not applicable');
            $table->string('type')->default('takeaway')
                ->comment('Type of order, e.g. eat-in, takeaway, delivery');
            $table->string('status')->default('pending')
                ->comment('Order status, e.g., pending, completed, cancelled');

            $table->decimal('total_amount', 10, 2)
                ->comment('Total amount for the order');
            $table->decimal('tax_rate', 5, 2)->default(10.00)
                ->comment('Tax rate applied to the order (e.g., GST)');
            $table->decimal('tax_amount', 10, 2)->default(0.00)
                ->comment('Tax amount applied to the order (e.g., GST)');
            $table->decimal('discount_amount', 10, 2)->default(0.00)
                ->comment('Discount amount applied to the order');
            $table->decimal('final_amount', 10, 2)
                ->comment('Final amount after tax and discount applied to the order');
            $table->decimal('paid_amount', 10, 2)
                ->comment('Amount paid for the order');
            $table->string('payment_method')->nullable()
                ->comment('Payment method used for the order, e.g., pos, cash, voucher, split');
            $table->string('tag')->nullable()
                ->comment('Tag for the order, e.g., desk_order, customer_order, manual_order');
            $table->string('note')->nullable()
                ->comment('Additional notes for the order by the customer');
            $table->string('remark')->nullable()
                ->comment('Additional remarks of this order by the staff');
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
