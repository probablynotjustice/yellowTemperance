<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/** Ill Learn how to build a factory another time.
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

        ];
}

}
