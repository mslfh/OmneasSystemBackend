<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific products for each category
        $this->createMealProducts();
        $this->createSoupProducts();
        $this->createDrinkProducts();
        $this->createSnackProducts();

        // Assign profiles to products
        $this->assignProfilesToProducts();

        $this->command->info('Created ' . Product::count() . ' products successfully.');
    }

    /**
     * Create meal products
     */
    private function createMealProducts(): void
    {
        $mealCategory = Category::where('title', 'Meal')->whereNull('parent_id')->first();
        $friedRiceCategory = Category::where('title', 'Fried Rice')->first();
        $noodleCategory = Category::where('title', 'Noodle')->first();


        $friedRiceProducts = [
            [
                'code' => 'M004',
                'title' => 'Nasi Goreng',
                'second_title' => '印尼炒饭',
                'acronym' => 'NG',
                'description' => 'Indonesian-style fried rice with aromatic spices and traditional flavors',
                'tip' => 'Spicy level adjustable, contains shrimp paste',
                'price' => 21.00,
                'discount' => 0,
                'selling_price' => 21.00,
                'stock' => 45,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=7',
                'image_list' => ['https://picsum.photos/400/300?random=7', 'https://picsum.photos/400/300?random=8'],
                'tag' => 'Indonesian,Spicy',
                'sort' => 4,
                'is_featured' => false,
            ],
            [
                'code' => 'M005',
                'title' => 'Combo Fried Rice',
                'second_title' => '综合炒饭',
                'acronym' => 'CFR',
                'description' => 'Mixed fried rice with various ingredients and rich flavors',
                'tip' => 'Contains eggs, suitable for sharing',
                'price' => 26.00,
                'discount' => 0,
                'selling_price' => 26.00,
                'stock' => 30,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=9',
                'image_list' => ['https://picsum.photos/400/300?random=9', 'https://picsum.photos/400/300?random=10'],
                'tag' => 'Popular,Combo',
                'sort' => 5,
                'is_featured' => true,
            ],

        ];

        $friedNoodleProducts = [
            [
                'code' => 'M001',
                'title' => 'Asia Box',
                'second_title' => '亚洲风味盒饭',
                'acronym' => 'AB',
                'description' => 'Traditional Asian flavors with premium ingredients and authentic cooking methods',
                'tip' => 'Best served hot, contains soy sauce',
                'price' => 25.00,
                'discount' => 0,
                'selling_price' => 25.00,
                'stock' => 50,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=1',
                'image_list' => ['https://picsum.photos/400/300?random=1', 'https://picsum.photos/400/300?random=2'],
                'tag' => 'Popular,Asian',
                'sort' => 1,
                'is_featured' => true,
            ],
            [
                'code' => 'M002',
                'title' => 'Yaki Udon Box',
                'second_title' => '日式炒乌冬盒饭',
                'acronym' => 'YUB',
                'description' => 'Japanese-style stir-fried udon noodles with vegetables and savory sauce',
                'tip' => 'Mild spice level, suitable for all ages',
                'price' => 28.00,
                'discount' => 6.00,
                'selling_price' => 22.00,
                'stock' => 40,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=3',
                'image_list' => ['https://picsum.photos/400/300?random=3', 'https://picsum.photos/400/300?random=4'],
                'tag' => 'Japanese,Deal',
                'sort' => 2,
                'is_featured' => false,
            ],
            [
                'code' => 'M003',
                'title' => 'Jade Green Box',
                'second_title' => '翡翠绿色盒饭',
                'acronym' => 'JGB',
                'description' => 'Fresh green vegetables with healthy ingredients for health-conscious diners',
                'tip' => 'Vegetarian friendly, low fat option',
                'price' => 23.00,
                'discount' => 0,
                'selling_price' => 23.00,
                'stock' => 35,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=5',
                'image_list' => ['https://picsum.photos/400/300?random=5', 'https://picsum.photos/400/300?random=6'],
                'tag' => 'Healthy,Vegetarian',
                'sort' => 3,
                'is_featured' => false,
            ],
            [
                'code' => 'M006',
                'title' => 'Prown Lovers',
                'second_title' => '虾仁爱好者',
                'acronym' => 'PL',
                'description' => 'Fresh prawns prepared with special sauce for seafood lovers',
                'tip' => 'Contains shellfish, please inform if allergic',
                'price' => 32.00,
                'discount' => 4.00,
                'selling_price' => 28.00,
                'stock' => 25,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=11',
                'image_list' => ['https://picsum.photos/400/300?random=11', 'https://picsum.photos/400/300?random=12'],
                'tag' => 'Seafood,Deal,Premium',
                'sort' => 6,
                'is_featured' => false,
            ],
            [
                'code' => 'M007',
                'title' => 'Sweet Box',
                'second_title' => '甜味盒饭',
                'acronym' => 'SB',
                'description' => 'Sweet and savory combination perfect for those who enjoy unique flavors',
                'tip' => 'Sweet and mild taste, loved by children',
                'price' => 24.00,
                'discount' => 0,
                'selling_price' => 24.00,
                'stock' => 40,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=13',
                'image_list' => ['https://picsum.photos/400/300?random=13', 'https://picsum.photos/400/300?random=14'],
                'tag' => 'Sweet,Mild',
                'sort' => 7,
                'is_featured' => false,
            ],
            [
                'code' => 'M008',
                'title' => 'MaMa MEE Box',
                'second_title' => '妈妈面盒饭',
                'acronym' => 'MMB',
                'description' => 'Homestyle instant noodles prepared with love and traditional methods',
                'tip' => 'Comfort food, quick preparation',
                'price' => 20.00,
                'discount' => 0,
                'selling_price' => 20.00,
                'stock' => 60,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=15',
                'image_list' => ['https://picsum.photos/400/300?random=15', 'https://picsum.photos/400/300?random=16'],
                'tag' => 'Comfort,Quick',
                'sort' => 8,
                'is_featured' => false,
            ],
            [
                'code' => 'M009',
                'title' => 'Hokkie MEE Box',
                'second_title' => '福建面盒饭',
                'acronym' => 'HMB',
                'description' => 'Traditional Hokkien-style noodles with rich broth and premium toppings',
                'tip' => 'Traditional recipe, contains pork',
                'price' => 27.00,
                'discount' => 0,
                'selling_price' => 27.00,
                'stock' => 35,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=17',
                'image_list' => ['https://picsum.photos/400/300?random=17', 'https://picsum.photos/400/300?random=18'],
                'tag' => 'Traditional,Hokkien',
                'sort' => 9,
                'is_featured' => false,
            ],
            [
                'code' => 'M010',
                'title' => 'Singapore Noodles',
                'second_title' => '新加坡米粉',
                'acronym' => 'SN',
                'description' => 'Curry-flavored rice noodles with a perfect blend of spices and ingredients',
                'tip' => 'Contains curry powder, mildly spicy',
                'price' => 25.50,
                'discount' => 0,
                'selling_price' => 25.50,
                'stock' => 42,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=19',
                'image_list' => ['https://picsum.photos/400/300?random=19', 'https://picsum.photos/400/300?random=20'],
                'tag' => 'Singapore,Curry',
                'sort' => 10,
                'is_featured' => true,
            ],
            [
                'code' => 'M011',
                'title' => 'Seafood Box',
                'second_title' => '海鲜盒饭',
                'acronym' => 'SFB',
                'description' => 'Fresh seafood combination with vegetables in special sauce',
                'tip' => 'Contains various seafood, please check for allergies',
                'price' => 30.00,
                'discount' => 0,
                'selling_price' => 30.00,
                'stock' => 20,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=21',
                'image_list' => ['https://picsum.photos/400/300?random=21', 'https://picsum.photos/400/300?random=22'],
                'tag' => 'Seafood,Premium',
                'sort' => 11,
                'is_featured' => false,
            ],
            [
                'code' => 'M012',
                'title' => 'Hot Box',
                'second_title' => '火辣盒饭',
                'acronym' => 'HB',
                'description' => 'Spicy and flavorful dish for those who love heat and bold flavors',
                'tip' => 'Very spicy, not recommended for children',
                'price' => 32.00,
                'discount' => 10.00,
                'selling_price' => 22.00,
                'stock' => 38,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=23',
                'image_list' => ['https://picsum.photos/400/300?random=23', 'https://picsum.photos/400/300?random=24'],
                'tag' => 'Spicy,Hot,Deal',
                'sort' => 12,
                'is_featured' => false,
            ],
        ];

        foreach ($friedRiceProducts as $productData) {
            $product = Product::create($productData);
            if ($friedRiceCategory) {
                // Attach the product to the fried rice category and meal category
                $product->categories()->attach([
                    $friedRiceCategory->id,
                    $mealCategory->id,
                ]);
            }
        }

        foreach ($friedNoodleProducts as $productData) {
            $product = Product::create($productData);
            if ($noodleCategory) {
                // Attach the product to the noodle category and meal category
                $product->categories()->attach([
                    $noodleCategory->id,
                    $mealCategory->id,
                ]);
            }
        }

        $this->command->info('Created Meal products.');
    }

    /**
     * Create soup products
     */
    private function createSoupProducts(): void
    {
        $soupCategory = Category::where('title', 'Soup')->whereNull('parent_id')->first();

        $soupProducts = [
            [
                'code' => 'S001',
                'title' => 'TomYum Soup',
                'second_title' => '冬阴功汤',
                'acronym' => 'TYS',
                'description' => 'Traditional Thai hot and sour soup with aromatic herbs and spices',
                'tip' => 'Spicy and sour, contains lemongrass',
                'price' => 25.00,
                'discount' => 0,
                'selling_price' => 25.00,
                'stock' => 40,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=25',
                'image_list' => ['https://picsum.photos/400/300?random=25', 'https://picsum.photos/400/300?random=26'],
                'tag' => 'Thai,Spicy,Popular',
                'sort' => 1,
                'is_featured' => true,
            ],
            [
                'code' => 'S002',
                'title' => 'Malay Curry Laksa Soup',
                'second_title' => '马来咖喱叻沙汤',
                'acronym' => 'MCLS',
                'description' => 'Rich and creamy Malay-style curry soup with coconut milk and spices',
                'tip' => 'Contains coconut milk, mildly spicy',
                'price' => 28.00,
                'discount' => 0,
                'selling_price' => 28.00,
                'stock' => 35,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=27',
                'image_list' => ['https://picsum.photos/400/300?random=27', 'https://picsum.photos/400/300?random=28'],
                'tag' => 'Malay,Curry,Creamy',
                'sort' => 2,
                'is_featured' => false,
            ],
            [
                'code' => 'S003',
                'title' => 'WonTon Soup',
                'second_title' => '云吞汤',
                'acronym' => 'WTS',
                'description' => 'Traditional Chinese soup with handmade wontons in clear broth',
                'tip' => 'Contains pork and shrimp filling',
                'price' => 22.00,
                'discount' => 0,
                'selling_price' => 22.00,
                'stock' => 50,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=29',
                'image_list' => ['https://picsum.photos/400/300?random=29', 'https://picsum.photos/400/300?random=30'],
                'tag' => 'Chinese,Traditional,Light',
                'sort' => 3,
                'is_featured' => false,
            ],
            [
                'code' => 'S004',
                'title' => 'Fish Ball Soup',
                'second_title' => '鱼丸汤',
                'acronym' => 'FBS',
                'description' => 'Fresh fish balls in clear broth with vegetables',
                'tip' => 'Light and healthy, suitable for all ages',
                'price' => 20.00,
                'discount' => 0,
                'selling_price' => 20.00,
                'stock' => 45,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=31',
                'image_list' => ['https://picsum.photos/400/300?random=31', 'https://picsum.photos/400/300?random=32'],
                'tag' => 'Light,Healthy,Fish',
                'sort' => 4,
                'is_featured' => false,
            ],
            [
                'code' => 'S005',
                'title' => 'BBQ Pork Soup',
                'second_title' => '叉烧汤',
                'acronym' => 'BPS',
                'description' => 'Savory soup with tender BBQ pork slices in rich broth',
                'tip' => 'Contains BBQ pork, rich flavor',
                'price' => 26.00,
                'discount' => 3.00,
                'selling_price' => 23.00,
                'stock' => 30,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=33',
                'image_list' => ['https://picsum.photos/400/300?random=33', 'https://picsum.photos/400/300?random=34'],
                'tag' => 'BBQ,Pork,Deal',
                'sort' => 5,
                'is_featured' => false,
            ],
            [
                'code' => 'S006',
                'title' => 'Seafood Hot Soup',
                'second_title' => '海鲜热汤',
                'acronym' => 'SHS',
                'description' => 'Mixed seafood in spicy hot broth with vegetables',
                'tip' => 'Contains various seafood, spicy level adjustable',
                'price' => 30.00,
                'discount' => 0,
                'selling_price' => 30.00,
                'stock' => 25,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=35',
                'image_list' => ['https://picsum.photos/400/300?random=35', 'https://picsum.photos/400/300?random=36'],
                'tag' => 'Seafood,Spicy,Premium',
                'sort' => 6,
                'is_featured' => true,
            ],
            [
                'code' => 'S007',
                'title' => 'Cream Corn Soup',
                'second_title' => '奶油玉米汤',
                'acronym' => 'CCS',
                'description' => 'Creamy corn soup with sweet kernels and smooth texture',
                'tip' => 'Vegetarian friendly, contains dairy',
                'price' => 21.00,
                'discount' => 0,
                'selling_price' => 21.00,
                'stock' => 55,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=37',
                'image_list' => ['https://picsum.photos/400/300?random=37', 'https://picsum.photos/400/300?random=38'],
                'tag' => 'Vegetarian,Creamy,Sweet',
                'sort' => 7,
                'is_featured' => false,
            ],
        ];

        foreach ($soupProducts as $productData) {
            $product = Product::create($productData);
            if ($soupCategory) {
                $product->categories()->attach($soupCategory->id);
            }
        }

        $this->command->info('Created ' . count($soupProducts) . ' soup products.');
    }

    /**
     * Create drink products
     */
    private function createDrinkProducts(): void
    {
        $drinkCategory = Category::where('title', 'Drink')->whereNull('parent_id')->first();
        $tinCategory = Category::where('title', '200ml Tin')->first();
        $bottleCategory = Category::where('title', '600ml bottle')->first();

        $tinProducts = [
            [
                'code' => 'D001',
                'title' => 'Cola (Can)',
                'second_title' => '可乐罐装',
                'acronym' => 'CC',
                'description' => 'Classic cola in convenient can size',
                'tip' => '200ml tin, contains caffeine',
                'price' => 18.00,
                'discount' => 0,
                'selling_price' => 18.00,
                'stock' => 120,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=39',
                'image_list' => ['https://picsum.photos/400/300?random=39', 'https://picsum.photos/400/300?random=40'],
                'tag' => 'Cola,Tin,Classic',
                'sort' => 1,
                'is_featured' => true,
            ],
        ];

        $bottleProducts = [
            [
                'code' => 'D002',
                'title' => 'Cola (Bottle)',
                'second_title' => '可乐瓶装',
                'acronym' => 'CB',
                'description' => 'Classic cola in convenient bottle size',
                'tip' => '600ml bottle, contains caffeine',
                'price' => 25.00,
                'discount' => 0,
                'selling_price' => 25.00,
                'stock' => 80,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=41',
                'image_list' => ['https://picsum.photos/400/300?random=41', 'https://picsum.photos/400/300?random=42'],
                'tag' => 'Cola,Bottle,Classic',
                'sort' => 2,
                'is_featured' => false,
            ],
        ];

        // Create tin products
        foreach ($tinProducts as $productData) {
            $product = Product::create($productData);
            if ($tinCategory && $drinkCategory) {
                // Attach the product to both the tin subcategory and main drink category
                $product->categories()->attach([
                    $tinCategory->id,
                    $drinkCategory->id,
                ]);
            }
        }

        // Create bottle products
        foreach ($bottleProducts as $productData) {
            $product = Product::create($productData);
            if ($bottleCategory && $drinkCategory) {
                // Attach the product to both the bottle subcategory and main drink category
                $product->categories()->attach([
                    $bottleCategory->id,
                    $drinkCategory->id,
                ]);
            }
        }

        $this->command->info('Created ' . (count($tinProducts) + count($bottleProducts)) . ' drink products.');
    }

    /**
     * Create snack products
     */
    private function createSnackProducts(): void
    {
        $snackCategory = Category::where('title', 'Snack')->whereNull('parent_id')->first();

        $snackProducts = [
            [
                'code' => 'SN001',
                'title' => 'Spring Roll',
                'second_title' => '春卷',
                'acronym' => 'SR',
                'description' => 'Crispy spring rolls filled with fresh vegetables and served with sweet chili sauce',
                'tip' => 'Vegetarian friendly, served hot',
                'price' => 18.00,
                'discount' => 0,
                'selling_price' => 18.00,
                'stock' => 60,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=55',
                'image_list' => ['https://picsum.photos/400/300?random=55', 'https://picsum.photos/400/300?random=56'],
                'tag' => 'Vegetarian,Crispy,Popular',
                'sort' => 1,
                'is_featured' => true,
            ],
            [
                'code' => 'SN002',
                'title' => 'Fried DimSim',
                'second_title' => '炸点心',
                'acronym' => 'FDS',
                'description' => 'Golden fried dim sim with savory filling and crispy exterior',
                'tip' => 'Contains pork and vegetables, best served hot',
                'price' => 22.00,
                'discount' => 0,
                'selling_price' => 22.00,
                'stock' => 50,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=57',
                'image_list' => ['https://picsum.photos/400/300?random=57', 'https://picsum.photos/400/300?random=58'],
                'tag' => 'Fried,DimSim,Popular',
                'sort' => 2,
                'is_featured' => false,
            ],
            [
                'code' => 'SN003',
                'title' => 'Steamed DimSim',
                'second_title' => '蒸点心',
                'acronym' => 'SDS',
                'description' => 'Traditional steamed dim sim with tender filling and soft wrapper',
                'tip' => 'Contains pork and vegetables, healthier option',
                'price' => 20.00,
                'discount' => 0,
                'selling_price' => 20.00,
                'stock' => 55,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=59',
                'image_list' => ['https://picsum.photos/400/300?random=59', 'https://picsum.photos/400/300?random=60'],
                'tag' => 'Steamed,DimSim,Healthy',
                'sort' => 3,
                'is_featured' => false,
            ],
            [
                'code' => 'SN004',
                'title' => 'Curry Puff',
                'second_title' => '咖喱泡芙',
                'acronym' => 'CP',
                'description' => 'Flaky pastry filled with spiced curry potato and vegetables',
                'tip' => 'Contains curry spices, mildly spicy',
                'price' => 16.00,
                'discount' => 0,
                'selling_price' => 16.00,
                'stock' => 70,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=61',
                'image_list' => ['https://picsum.photos/400/300?random=61', 'https://picsum.photos/400/300?random=62'],
                'tag' => 'Curry,Pastry,Spicy',
                'sort' => 4,
                'is_featured' => false,
            ],
            [
                'code' => 'SN005',
                'title' => 'Prawn Cracker',
                'second_title' => '虾片',
                'acronym' => 'PC',
                'description' => 'Light and crispy prawn crackers with authentic seafood flavor',
                'tip' => 'Contains seafood, gluten-free option',
                'price' => 14.00,
                'discount' => 0,
                'selling_price' => 14.00,
                'stock' => 80,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=63',
                'image_list' => ['https://picsum.photos/400/300?random=63', 'https://picsum.photos/400/300?random=64'],
                'tag' => 'Prawn,Crispy,Gluten-Free',
                'sort' => 5,
                'is_featured' => true,
            ],
        ];

        foreach ($snackProducts as $productData) {
            $product = Product::create($productData);
            if ($snackCategory) {
                $product->categories()->attach($snackCategory->id);
            }
        }

        $this->command->info('Created ' . count($snackProducts) . ' snack products.');
    }

    /**
     * Assign profiles to products
     */
    private function assignProfilesToProducts(): void
    {
        $products = Product::all();
        $profiles = Profile::where('status', 'active')->get();

        if ($profiles->count() > 0) {
            foreach ($products as $product) {
                $product->profiles()->attach($profiles->pluck('id')->toArray());
            }
            $this->command->info('Assigned profiles to products.');
        } else {
            $this->command->info('No active profiles found to assign to products.');
        }
    }
}
