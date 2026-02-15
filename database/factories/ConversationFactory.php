<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversation>
 */
class ConversationFactory extends Factory
{
    public function definition(): array
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        return [
            'listing_id' => Listing::factory()->for($seller),
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'subject' => fake()->sentence(),
            'last_message_at' => now(),
        ];
    }

    public function forListing(Listing $listing): static
    {
        return $this->state(fn (array $attributes) => [
            'listing_id' => $listing->id,
            'seller_id' => $listing->user_id,
        ]);
    }

    public function between(User $buyer, User $seller): static
    {
        return $this->state(fn (array $attributes) => [
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
        ]);
    }
}
