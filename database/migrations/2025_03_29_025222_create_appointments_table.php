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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_first')->default(false);
            $table->string('status')->default('booked')
                ->comment('pending, booked, cancelled, in_progress, finished');
            $table->string('type')
                ->comment('unassigned, assigned, break, no_show');

            $table->string('customer_id')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('comments')->nullable();

            $table->string('tag')->nullable();

            $table->dateTime('booking_time')->comment('The time the appointment was booked');

            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();

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
