<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->secondaryAddress(),
            'price' => fake()->numberBetween(1000, 10000000),
        ];
    }
}
