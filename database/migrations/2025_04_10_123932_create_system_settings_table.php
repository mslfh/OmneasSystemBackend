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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Insert default system settings
        \DB::table('system_settings')->insert([
            ['key' => 'opening_time', 'value' => '08:00', 'description' => 'Opening time of the system', 'type' => 'time', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'closing_time', 'value' => '19:00', 'description' => 'Closing time of the system', 'type' => 'time', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'booking_reminder', 'value' => 'false', 'description' => 'If send reminder message that the appointment has been successfully made', 'type' => 'boolean', 'created_at' => now(), 'updated_at' => now()],
            [
                'key' => 'booking_reminder_msg',
                'value' => "Hi {first_name} {last_name}, your appointment at Mia's Massage Therapy is confirmed.
Details: {service} with {therapist} on {date} at {time}.
For changes or cancellations, call +61 466 605 855.
We look forward to seeing you!",
                'description' => 'A reminder message that the appointment has been successfully made',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            ['key' => 'reminder_interval', 'value' => '0', 'description' => 'Number of hours before the appointment to automatically send a reminder. 0 - no reminder', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            [
                'key' => 'reminder_msg',
                'value' => "Hi {first_name} {last_name}, this is a kind reminder: your {service} with {therapist} is on {date} at {time}, Mia's Massage Therapy.
Call +61 466 605 855 if needed.
See you soon!",
                'description' => 'Message for appointment reminder',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
