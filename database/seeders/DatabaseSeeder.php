<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
use App\Models\SpecialRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' =>  env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => env('ADMIN_DEFAULT_PASSWORD')
        ]);

        $desk = User::factory()->create([
            'name' =>  env('DESK_NAME'),
            'email' => env('DESK_EMAIL'),
            'password' => env('DESK_DEFAULT_PASSWORD')
        ]);

        SpecialRole::create([
            'name' => 'Admin',
            'description' => 'Administrator Role',
            'is_active' => true,
            'user_id' => $admin->id
        ]);
        SpecialRole::create([
            'name' => 'Desk',
            'description' => 'Front Desk Role',
            'is_active' => true,
            'user_id' => $desk->id
        ]);

        SystemSetting::create([
            'key' => 'opening_time',
            'value' => '08:00',
            'description' => 'Opening time of the system',
            'type' => 'time'
        ]);
        SystemSetting::create([
            'key' => 'closing_time',
            'value' => '19:00',
            'description' => 'Closing time of the system',
            'type' => 'time'
        ]);
        SystemSetting::create([
            'key' => 'booking_reminder',
            'value' => 'true',
            'description' => 'If send reminder message that the appointment has been successfully made',
            'type' => 'boolean'
        ]);
        SystemSetting::create([
            'key' => 'booking_reminder_msg',
            'value' => '',
            'description' => 'A reminder message that the appointment has been successfully made',
            'type' => 'textarea'
        ]);
        SystemSetting::create([
            'key' => 'reminder_interval',
            'value' => '24',
            'description' => 'Number of hours before the appointment to automatically send a reminder. 0 - no reminder',
            'type' => 'number'
        ]);
        SystemSetting::create([
            'key' => 'reminder_msg',
            'value' => '',
            'description' => 'Message for appointment reminder',
            'type' => 'textarea'
        ]);
    }
}
