<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'MAIN' => [
                'Thick Egg Noodle', 'Thin Egg Noodle', 'Thin Rice Noodle', 'Udon Noodle',
                'Flat Rice Noodle', 'Spinach Noodle', 'Rice', 'Fresh Egg Noodle(Soup Only)'
            ],
            'VEGETABLE' => [
                'Pineapple', 'Cabbage', 'Mushroom', 'Capsicum', 'Tofu', 'Tomato', 'Onion', 'Bokchoy', 'Carrot'
            ],
            'MEAT' => [
                'BBQ Pork', 'Beef', 'Chicken', 'Egg'
            ],
            'SEAFOOD' => [
                'Prawn', 'Shrimp', 'FishCake', 'Squid', 'SeafoodEXT'
            ],
            'SOURCE' => [
                'Asia Source', 'Yaki Source', 'Satay Source', 'Gluten Free( Singapore)', 'HotBox Source',
                'HokkieMEE Source', 'Sambal Source', 'MaMaMEE Source', 'SweetBox Source', 'BlackBean Source',
                'Garlic Source', 'SweetHoney Source', 'Oyster Source'
            ]
        ];

        foreach ($data as $type => $items) {
            foreach ($items as $name) {
                Item::updateOrCreate(
                    ['type' => $type, 'name' => $name],
                    [
                        'description' => 'Premium quality ingredient',
                        'price' => $this->getDefaultPrice($type)
                    ]
                );
            }
        }

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Seeded Items: ' . Item::count());
            foreach (array_keys($data) as $type) {
                $count = Item::where('type', $type)->count();
                $this->command->info("$type items: $count");
            }
        }
    }

    private function getDefaultPrice(string $type): float
    {
        return match ($type) {
            'MAIN' => 5.00,
            'VEGETABLE' => 2.50,
            'MEAT' => 8.00,
            'SEAFOOD' => 10.00,
            'SOURCE' => 1.50,
            default => 3.00
        };
    }
}
