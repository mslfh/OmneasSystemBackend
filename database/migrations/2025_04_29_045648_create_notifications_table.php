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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('no')->comment('Third party Unique identifier for the notification')->nullable();
            $table->foreignId('appointment_id')->constrained('appointments')->nullable();
            $table->string('type')->nullable()->comment('Email, SMS, Push Notification');
            $table->string('recipient_name')->nullable()->comment('Recipient of the notification');
            $table->string('recipient_email')->nullable()->comment('Email of the recipient');
            $table->string('recipient_phone')->nullable()->comment('Phone number of the recipient');
            $table->string('subject')->comment('Subject of the notification');
            $table->text('content')->comment('Message content of the notification');
            $table->string('status')->default('pending')->comment('Status of the notification (pending, sent, failed)');
            $table->string('schedule_time')->nullable()->comment('Timestamp when the notification sent');
            $table->string('error_message')->nullable()->comment('Error message if the notification fails');
            $table->string('remark')->nullable()->comment('Additional remarks or comments about the notification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
