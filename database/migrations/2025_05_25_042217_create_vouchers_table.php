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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->string('type')->default('fixed')->comment(
                'Type of voucher: fixed, percentage, or one-time'
            );
            $table->decimal('discount_value', 10, 2)->default(0.00)->comment(
                'Value of the discount, either fixed amount or percentage'
            )->nullable();
            $table->string('notes')->nullable()->comment(
                'notes of the voucher'
            )->nullable();
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
