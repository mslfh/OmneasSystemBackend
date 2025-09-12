<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'EXTRA' => [
                'Extra Source', 'Extra Prawn', 'Extra Veg', 'Extra Bokchoy', 'Extra Chicken',
                'Extra Beef', 'Extra Seafood', 'Extra Pork', 'Extra 3Meat', 'Extra Tofu',
                'Extra Noodle', 'Extra Rice',
            ],
            'CHILLI' => [
                'No Chilli', 'Mild Chilli', 'Extra Chilli1', 'Extra Chilli2', 'Extra Chilli3', 'Extra Chilli5',
            ],
            'NO' => [
                'No Veg', 'No Onion', 'No Carrot', 'No Bokchoy', 'No Pineapple', 'No Cabbage', 'No Mushroom',
                'No Caps', 'No Tofu', 'No Garlic', 'No Tomato', 'No Seafood', 'No Beef', 'No Chicken', 'No Pork',
                'No Egg', 'No Noodles', 'No Squid', 'No FishCake', 'No Prawn', 'No Shrimp',
            ],
            'ONLY' => [
                'Only Chicken', 'Only Beef', 'Only Pork', 'Only Seafood', 'Only Veg', 'Only 3Meat', 'Only Noodles',
            ],
            'CHANGE' => [
                'Flat Noodle', 'Thick Egg Noodle', 'Thin Rice', 'Thin Egg Noodle','Udon Noodle',
                'Asia Source', 'Yaki Source', 'Satay Source', 'Gluten Free( Singapore)', 'HotBox Source',
                'HokkieMEE Source', 'Sambal Source', 'MaMaMEE Source', 'SweetBox Source', 'BlackBean Source',
                'Garlic Source', 'SweetHoney Source', 'Oyster Source'
            ]
        ];

        foreach ($data as $type => $names) {
            foreach ($names as $name) {
                Attribute::updateOrCreate(
                    ['type' => $type, 'name' => $name],
                    ['extra_cost' => rand(0, 5)]
                );
            }
        }

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Seeded Attributes: ' . Attribute::count());
        }
    }
}
