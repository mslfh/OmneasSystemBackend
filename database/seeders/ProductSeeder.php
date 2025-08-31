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

        $mealProducts = [
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
                'title' => 'Combo Frid Rice',
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

        foreach ($mealProducts as $productData) {
            $product = Product::create($productData);
            if ($mealCategory) {
                $product->categories()->attach($mealCategory->id);
            }
        }

        $this->command->info('Created ' . count($mealProducts) . ' meal products.');
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

        $drinkProducts = [
            [
                'code' => 'D001',
                'title' => 'Fresh Orange Juice',
                'second_title' => '鲜榨橙汁',
                'acronym' => 'FOJ',
                'description' => 'Freshly squeezed orange juice with natural sweetness',
                'tip' => 'No added sugar, 100% natural',
                'price' => 25.00,
                'discount' => 0,
                'selling_price' => 25.00,
                'stock' => 80,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=39',
                'image_list' => ['https://picsum.photos/400/300?random=39', 'https://picsum.photos/400/300?random=40'],
                'tag' => 'Fresh,Natural,Healthy',
                'sort' => 1,
                'is_featured' => true,
            ],
            [
                'code' => 'D002',
                'title' => 'Green Tea',
                'second_title' => '绿茶',
                'acronym' => 'GT',
                'description' => 'Traditional green tea with antioxidants and refreshing taste',
                'tip' => 'Contains caffeine, hot or cold available',
                'price' => 20.00,
                'discount' => 0,
                'selling_price' => 20.00,
                'stock' => 100,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=41',
                'image_list' => ['https://picsum.photos/400/300?random=41', 'https://picsum.photos/400/300?random=42'],
                'tag' => 'Tea,Healthy,Antioxidant',
                'sort' => 2,
                'is_featured' => false,
            ],
            [
                'code' => 'D003',
                'title' => 'Iced Lemon Tea',
                'second_title' => '冰柠檬茶',
                'acronym' => 'ILT',
                'description' => 'Refreshing iced tea with fresh lemon slices',
                'tip' => 'Refreshing and cooling, perfect for hot weather',
                'price' => 25.00,
                'discount' => 3.00,
                'selling_price' => 22.00,
                'stock' => 90,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=43',
                'image_list' => ['https://picsum.photos/400/300?random=43', 'https://picsum.photos/400/300?random=44'],
                'tag' => 'Iced,Lemon,Deal',
                'sort' => 3,
                'is_featured' => false,
            ],
            [
                'code' => 'D004',
                'title' => 'Coconut Water',
                'second_title' => '椰子水',
                'acronym' => 'CW',
                'description' => 'Natural coconut water with electrolytes and tropical flavor',
                'tip' => 'Natural electrolytes, perfect for hydration',
                'price' => 28.00,
                'discount' => 0,
                'selling_price' => 28.00,
                'stock' => 60,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=45',
                'image_list' => ['https://picsum.photos/400/300?random=45', 'https://picsum.photos/400/300?random=46'],
                'tag' => 'Natural,Coconut,Electrolytes',
                'sort' => 4,
                'is_featured' => false,
            ],
            [
                'code' => 'D005',
                'title' => 'Mango Smoothie',
                'second_title' => '芒果奶昔',
                'acronym' => 'MS',
                'description' => 'Creamy mango smoothie made with fresh mangoes and yogurt',
                'tip' => 'Contains dairy, rich in vitamins',
                'price' => 30.00,
                'discount' => 0,
                'selling_price' => 30.00,
                'stock' => 50,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=47',
                'image_list' => ['https://picsum.photos/400/300?random=47', 'https://picsum.photos/400/300?random=48'],
                'tag' => 'Smoothie,Mango,Creamy',
                'sort' => 5,
                'is_featured' => true,
            ],
            [
                'code' => 'D006',
                'title' => 'Cola (Can)',
                'second_title' => '可乐罐装',
                'acronym' => 'CC',
                'description' => 'Classic cola in convenient can size',
                'tip' => '320ml can, contains caffeine',
                'price' => 21.00,
                'discount' => 0,
                'selling_price' => 21.00,
                'stock' => 120,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=49',
                'image_list' => ['https://picsum.photos/400/300?random=49', 'https://picsum.photos/400/300?random=50'],
                'tag' => 'Cola,Can,Classic',
                'sort' => 6,
                'is_featured' => false,
            ],
            [
                'code' => 'D007',
                'title' => 'Coffee Latte',
                'second_title' => '咖啡拿铁',
                'acronym' => 'CL',
                'description' => 'Rich espresso with steamed milk and smooth foam',
                'tip' => 'Contains caffeine and dairy, available hot or iced',
                'price' => 26.00,
                'discount' => 0,
                'selling_price' => 26.00,
                'stock' => 70,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=51',
                'image_list' => ['https://picsum.photos/400/300?random=51', 'https://picsum.photos/400/300?random=52'],
                'tag' => 'Coffee,Latte,Caffeine',
                'sort' => 7,
                'is_featured' => false,
            ],
            [
                'code' => 'D008',
                'title' => 'Watermelon Juice',
                'second_title' => '西瓜汁',
                'acronym' => 'WJ',
                'description' => 'Fresh watermelon juice, naturally sweet and hydrating',
                'tip' => 'Seasonal fruit, perfect for summer',
                'price' => 24.00,
                'discount' => 0,
                'selling_price' => 24.00,
                'stock' => 45,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=53',
                'image_list' => ['https://picsum.photos/400/300?random=53', 'https://picsum.photos/400/300?random=54'],
                'tag' => 'Fresh,Watermelon,Summer',
                'sort' => 8,
                'is_featured' => false,
            ],
        ];

        foreach ($drinkProducts as $productData) {
            $product = Product::create($productData);
            if ($drinkCategory) {
                $product->categories()->attach($drinkCategory->id);
            }
        }

        $this->command->info('Created ' . count($drinkProducts) . ' drink products.');
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
                'title' => 'Crispy Chicken Wings',
                'second_title' => '香脆鸡翅',
                'acronym' => 'CCW',
                'description' => 'Golden crispy chicken wings with special seasoning',
                'tip' => 'Best served hot, contains bones',
                'price' => 28.00,
                'discount' => 0,
                'selling_price' => 28.00,
                'stock' => 60,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=55',
                'image_list' => ['https://picsum.photos/400/300?random=55', 'https://picsum.photos/400/300?random=56'],
                'tag' => 'Crispy,Chicken,Popular',
                'sort' => 1,
                'is_featured' => true,
            ],
            [
                'code' => 'SN002',
                'title' => 'French Fries',
                'second_title' => '薯条',
                'acronym' => 'FF',
                'description' => 'Golden crispy french fries with perfect seasoning',
                'tip' => 'Served with ketchup, best eaten fresh',
                'price' => 22.00,
                'discount' => 0,
                'selling_price' => 22.00,
                'stock' => 80,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=57',
                'image_list' => ['https://picsum.photos/400/300?random=57', 'https://picsum.photos/400/300?random=58'],
                'tag' => 'Crispy,Potato,Classic',
                'sort' => 2,
                'is_featured' => false,
            ],
            [
                'code' => 'SN003',
                'title' => 'Onion Rings',
                'second_title' => '洋葱圈',
                'acronym' => 'OR',
                'description' => 'Crispy battered onion rings with sweet onion inside',
                'tip' => 'Vegetarian friendly, served hot',
                'price' => 26.00,
                'discount' => 4.00,
                'selling_price' => 22.00,
                'stock' => 45,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=59',
                'image_list' => ['https://picsum.photos/400/300?random=59', 'https://picsum.photos/400/300?random=60'],
                'tag' => 'Vegetarian,Crispy,Deal',
                'sort' => 3,
                'is_featured' => false,
            ],
            [
                'code' => 'SN004',
                'title' => 'Mixed Nuts',
                'second_title' => '综合坚果',
                'acronym' => 'MN',
                'description' => 'Premium mixed nuts with almonds, cashews, and peanuts',
                'tip' => 'Contains nuts, healthy snack option',
                'price' => 25.00,
                'discount' => 0,
                'selling_price' => 25.00,
                'stock' => 40,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=61',
                'image_list' => ['https://picsum.photos/400/300?random=61', 'https://picsum.photos/400/300?random=62'],
                'tag' => 'Healthy,Nuts,Premium',
                'sort' => 4,
                'is_featured' => false,
            ],
            [
                'code' => 'SN005',
                'title' => 'Chocolate Brownies',
                'second_title' => '巧克力布朗尼',
                'acronym' => 'CB',
                'description' => 'Rich chocolate brownies with fudgy texture',
                'tip' => 'Contains dairy and eggs, sweet dessert',
                'price' => 24.00,
                'discount' => 0,
                'selling_price' => 24.00,
                'stock' => 35,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=63',
                'image_list' => ['https://picsum.photos/400/300?random=63', 'https://picsum.photos/400/300?random=64'],
                'tag' => 'Sweet,Chocolate,Dessert',
                'sort' => 5,
                'is_featured' => true,
            ],
            [
                'code' => 'SN006',
                'title' => 'Cheese Sticks',
                'second_title' => '芝士条',
                'acronym' => 'CS',
                'description' => 'Crispy breaded cheese sticks with melted cheese inside',
                'tip' => 'Contains dairy, served with marinara sauce',
                'price' => 26.00,
                'discount' => 0,
                'selling_price' => 26.00,
                'stock' => 50,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=65',
                'image_list' => ['https://picsum.photos/400/300?random=65', 'https://picsum.photos/400/300?random=66'],
                'tag' => 'Cheese,Crispy,Popular',
                'sort' => 6,
                'is_featured' => false,
            ],
            [
                'code' => 'SN007',
                'title' => 'Fish & Chips',
                'second_title' => '炸鱼薯条',
                'acronym' => 'FC',
                'description' => 'Golden battered fish with crispy chips, served with tartar sauce',
                'tip' => 'Contains fish, served with lemon wedge',
                'price' => 30.00,
                'discount' => 0,
                'selling_price' => 30.00,
                'stock' => 30,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=67',
                'image_list' => ['https://picsum.photos/400/300?random=67', 'https://picsum.photos/400/300?random=68'],
                'tag' => 'Fish,Classic,British',
                'sort' => 7,
                'is_featured' => false,
            ],
            [
                'code' => 'SN008',
                'title' => 'Calamari Rings',
                'second_title' => '鱿鱼圈',
                'acronym' => 'CR',
                'description' => 'Tender squid rings with crispy coating and spicy mayo',
                'tip' => 'Contains seafood, served with aioli sauce',
                'price' => 29.00,
                'discount' => 7.00,
                'selling_price' => 22.00,
                'stock' => 25,
                'status' => 'active',
                'image' => 'https://picsum.photos/400/300?random=69',
                'image_list' => ['https://picsum.photos/400/300?random=69', 'https://picsum.photos/400/300?random=70'],
                'tag' => 'Seafood,Crispy,Deal',
                'sort' => 8,
                'is_featured' => false,
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
                // 30% of products will be assigned to profiles
                if (rand(1, 100) <= 30) {
                    $randomProfiles = $profiles->random(rand(1, min(2, $profiles->count())));
                    $product->profiles()->attach($randomProfiles->pluck('id')->toArray());
                }
            }

            $this->command->info('Assigned profiles to products.');
        } else {
            $this->command->info('No active profiles found to assign to products.');
        }
    }
}
