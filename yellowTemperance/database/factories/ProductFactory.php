<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Trading Cards' => [
                'Pokémon Card',
                'Magic Card',
                'Yu-Gi-Oh Card',
                'Baseball Card',
            ],

            'Electronics' => [
                'Gaming PC',
                'Laptop',
                'Drone',
                'Camera',
            ],

            'Luxury' => [
                'Rolex Watch',
                'Diamond Ring',
                'Gold Necklace',
            ],

            'Collectibles' => [
                'Comic Book',
                'Action Figure',
                'LEGO Set',
                'Vinyl Record',
            ],
        ];

        $adjectives = [
            'Vintage',
            'Rare',
            'Limited Edition',
            'Collector\'s',
            'Signed',
            'Antique',
            'Classic',
            'Mint',
            'Premium',
            'Original',
        ];

        $category = fake()->randomElement(array_keys($categories));
        $item = fake()->randomElement($categories[$category]);

        $retail = fake()->numberBetween(100, 5000);
        $price = fake()->numberBetween(50, $retail);

        return [
            'name' => fake()->randomElement($adjectives) . ' ' . $item,
            'description' =>
                "A {$item} in excellent condition. "
                . fake()->sentence()
                . " Perfect for collectors and enthusiasts.",

            'retail_price' => $retail,
            'price' => $price,
            'inventory' => fake()->numberBetween(1, 10),
            'vendor_id' => null,
            'category_id' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            for ($i = 0; $i < $product->inventory; $i++) {
                $product->units()->create([
                    'unique_id' => (string) Str::uuid(),
                    'status' => 'available',
                ]);
            }
        });
    }
}
