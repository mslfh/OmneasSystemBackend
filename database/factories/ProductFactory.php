<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dishes = [
            // Main Dishes
            'Grilled Chicken Breast', 'Beef Stir Fry', 'Sweet and Sour Pork', 'Kung Pao Chicken', 'Braised Beef Brisket',
            'Steamed Fish Fillet', 'Teriyaki Salmon', 'BBQ Pork Ribs', 'Garlic Butter Shrimp', 'Honey Glazed Ham',
            'Chicken Fried Rice', 'Beef Noodle Soup', 'Seafood Pasta', 'Mushroom Risotto', 'Lamb Curry',
            // Soups
            'Tomato Egg Drop Soup', 'Chicken Corn Soup', 'Mushroom Cream Soup', 'Hot and Sour Soup', 'Seafood Bisque',
            'Miso Soup', 'French Onion Soup', 'Vegetable Broth', 'Beef Bone Soup', 'Wonton Soup',
            // Appetizers
            'Caesar Salad', 'Spring Rolls', 'Chicken Wings', 'Garlic Bread', 'Bruschetta',
            'Crispy Calamari', 'Stuffed Mushrooms', 'Cheese Platter', 'Mixed Nuts', 'Fruit Salad',
            // Desserts
            'Chocolate Cake', 'Tiramisu', 'Crème Brûlée', 'Cheesecake', 'Ice Cream Sundae',
            'Apple Pie', 'Lemon Tart', 'Panna Cotta', 'Macarons', 'Fruit Parfait'
        ];

        $secondTitles = [
            '主厨特色', '招牌推荐', '今日特惠', '限时供应', '经典口味',
            '新品上市', '人气爆款', '传统工艺', '健康之选', '精品优质'
        ];

        $descriptions = [
            'Made with premium ingredients using traditional cooking methods for rich, layered flavors',
            'Chef\'s signature recipe combining modern culinary techniques with nutritious and delicious taste',
            'Fresh ingredients sourced daily to ensure optimal taste and nutritional value',
            'Classic homestyle flavors that bring warmth and comfort to your dining experience',
            'Innovative cooking techniques perfectly blending traditional and modern culinary arts',
            'Seasonal ingredients creating limited-time gourmet experiences',
            'Healthy low-fat formula perfect for health-conscious diners',
            'Spicy and appetizing, perfect choice for stimulating your taste buds'
        ];

        $tips = [
            'Best served hot for optimal taste',
            'Mildly spicy, suitable for most palates',
            'Contains nuts, please inform if allergic',
            'Recommended to pair with rice',
            'Low sugar and low fat, healthy choice',
            'Spice level adjustable, please inform server',
            'Limited quantity, while supplies last',
            'Child-friendly with reduced seasoning'
        ];

        $tags = [
            'Special', 'Deal', 'New', 'Recommended', 'Spicy', 'Mild', 'Healthy',
            'Popular', 'Appetizing', 'Light', 'Nutritious', 'Low-fat', 'Sugar-free', 'Vegetarian'
        ];

        $price = $this->faker->randomFloat(2, 8, 120);
        $discount = $this->faker->randomFloat(2, 0, $price * 0.3);
        $sellingPrice = $price - $discount;

        return [
            'code' => 'P' . $this->faker->unique()->numberBetween(10000, 99999),
            'title' => $this->faker->randomElement($dishes),
            'second_title' => $this->faker->randomElement($secondTitles),
            'acronym' => $this->generateAcronym(),
            'description' => $this->faker->randomElement($descriptions),
            'tip' => $this->faker->randomElement($tips),
            'price' => $price,
            'discount' => $discount,
            'selling_price' => $sellingPrice,
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['active', 'inactive', 'out_of_stock']),
            'image' => $this->faker->imageUrl(400, 300, 'food'),
            'image_list' => [
                $this->faker->imageUrl(400, 300, 'food'),
                $this->faker->imageUrl(400, 300, 'food'),
                $this->faker->imageUrl(400, 300, 'food'),
            ],
            'tag' => implode(',', $this->faker->randomElements($tags, $this->faker->numberBetween(1, 4))),
            'sort' => $this->faker->numberBetween(1, 100),
            'is_featured' => $this->faker->boolean(30),
        ];
    }

    /**
     * Generate acronym for product
     */
    private function generateAcronym(): string
    {
        $acronyms = [
            'GCB', 'BSF', 'SSP', 'KPC', 'BBB',
            'SFF', 'TSA', 'BPR', 'GBS', 'HGH',
            'CFR', 'BNS', 'SP', 'MR', 'LC'
        ];

        return $this->faker->randomElement($acronyms);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'sort' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_stock',
            'stock' => 0,
        ]);
    }

    /**
     * Create a discounted product.
     */
    public function discounted(): static
    {
        return $this->state(function (array $attributes) {
            $price = $attributes['price'];
            $discount = $this->faker->randomFloat(2, $price * 0.1, $price * 0.4);
            return [
                'discount' => $discount,
                'selling_price' => $price - $discount,
                'tag' => $attributes['tag'] . ',Deal',
            ];
        });
    }
}
