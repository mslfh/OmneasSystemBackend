<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Package;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            Service::factory()->count(3)->create([
                'package_id' => $package->id,
            ]);
        }
    }
}
