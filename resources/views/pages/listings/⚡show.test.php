<?php

use App\Models\Listing;
use App\Models\User;

test('user bio is displayed on listing page when available', function () {
    $user = User::factory()->create(['bio' => 'Custom bio text']);
    $listing = Listing::factory()->for($user)->create();

    $this->actingAs($user);

    $this->get(route('listings.show', $listing))
        ->assertSee('Custom bio text')
        ->assertDontSee("Hello, I'm {$user->name}");
});

test('fallback text is shown when user has no bio on listing page', function () {
    $user = User::factory()->create(['bio' => null]);
    $listing = Listing::factory()->for($user)->create();

    $this->actingAs($user);

    $this->get(route('listings.show', $listing))
        ->assertSee("Hello, I'm {$user->name}");
});

test('fallback text is shown when user has empty bio on listing page', function () {
    $user = User::factory()->create(['bio' => '']);
    $listing = Listing::factory()->for($user)->create();

    $this->actingAs($user);

    $this->get(route('listings.show', $listing))
        ->assertSee("Hello, I'm {$user->name}");
});
