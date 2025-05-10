<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $users = User::limit(3)->orderBy('id', 'desc')->get();

        foreach ($users as $user) {
            Staff::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
