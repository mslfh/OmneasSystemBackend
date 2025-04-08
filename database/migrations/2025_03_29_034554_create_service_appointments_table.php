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
        Schema::create('service_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments');

            $table->dateTime('booking_time');
            $table->dateTime('expected_end_time')->nullable();

            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('package_id')->constrained('packages')->nullable();

            $table->string('package_title')->nullable();
            $table->string('package_hint')->nullable();

            $table->string('service_title')->nullable();
            $table->string('service_description')->nullable();
            $table->integer('service_duration')->nullable();
            $table->double('service_price')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('comments')->nullable();

            $table->foreignId('staff_id')->nullable();
            $table->string('staff_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_appointments');
    }
};
