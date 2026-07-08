<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $retail = fake()->numberBetween(150, 1000);
        $sale = fake()->numberBetween(50, $retail);

        return [
            'name' => fake()->words(3, true),

            'description' => fake()->paragraph(3),

            'retail_price' => $retail,

            'price' => $sale,

            'inventory' => fake()->numberBetween(1, 25),

            // We'll assign the vendor in the seeder.
            'vendor_id' => null,
        ];
    }
}

