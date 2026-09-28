<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startingBid = fake()->numberBetween(5, 100);
        $minimumIncrement = fake()->numberBetween(1, 20);
        $reservePrice = fake()->numberBetween(
            $startingBid,
            $startingBid * 10
        );
        $startsAt = fake()->dateTimeBetween('-2 days', '+2 days');
        $endsAt = fake()->dateTimeBetween(
            $startsAt,
            '+14 days'
        );
        return [
            'ticket_cost' => fake()->numberBetween(1, 10),
            'starting_bid' => $startingBid,
            'current_bid' => $startingBid,
            'minimum_increment' => $minimumIncrement,
            'reserve_price' => $reservePrice,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
        ];
    }
}
