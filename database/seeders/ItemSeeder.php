<?php

namespace Database\Seeders;

use App\Models\Item;
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
                        'price' => $this->getDefaultPrice($type),
                        'extra_price' => $this->getDefaultExtraPrice($type)
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
            'MAIN' => random_int(5, 10),
            'VEGETABLE' => random_int(2, 5),
            'MEAT' => random_int(8, 12),
            'SEAFOOD' => random_int(10, 15),
            'SOURCE' => random_int(1, 3),
            default => random_int(3, 6)
        };
    }

    private function getDefaultExtraPrice(string $type): float
    {
        return match ($type) {
            'MAIN' => random_int(1, 3),
            'VEGETABLE' => random_int(0, 2),
            'MEAT' => random_int(2, 4),
            'SEAFOOD' => random_int(3, 5),
            'SOURCE' => random_int(0, 1),
            default => random_int(1, 2)
        };
    }

}
