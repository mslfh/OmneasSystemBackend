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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->boolean('is_first')->default(false);
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_comments')->nullable();
            $table->string('tag')->nullable();

            $table->dateTime('booking_time');

            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();

            $table->string('status')->default('pending')
            ->comment('pending, confirmed, cancelled, in_progress, completed');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
