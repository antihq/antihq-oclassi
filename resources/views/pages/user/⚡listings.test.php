<?php

use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

test('user can close their listing', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test('pages::user.listings')
        ->call('toggleListingStatus', $listing->id)
        ->assertHasNoErrors();

    expect($listing->fresh()->closed_at)->not->toBeNull();
});

test('user can reopen their closed listing', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->closed()->create();

    Livewire::actingAs($user)
        ->test('pages::user.listings')
        ->call('toggleListingStatus', $listing->id)
        ->assertHasNoErrors();

    expect($listing->fresh()->closed_at)->toBeNull();
});

test('user cannot toggle another users listing', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $listing = Listing::factory()->for($owner)->create();

    Livewire::actingAs($otherUser)
        ->test('pages::user.listings')
        ->call('toggleListingStatus', $listing->id);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

test('closed listings do not appear in public index', function () {
    $openListing = Listing::factory()->create(['title' => 'Open Listing']);
    $closedListing = Listing::factory()->closed()->create(['title' => 'Closed Listing']);

    Livewire::test('pages::listings.index')
        ->assertSee('Open Listing')
        ->assertDontSee('Closed Listing');
});

test('closed listings appear in users own listings', function () {
    $user = User::factory()->create();
    $openListing = Listing::factory()->for($user)->create(['title' => 'Open Listing']);
    $closedListing = Listing::factory()->for($user)->closed()->create(['title' => 'Closed Listing']);

    Livewire::actingAs($user)
        ->test('pages::user.listings')
        ->assertSee('Open Listing')
        ->assertSee('Closed Listing');
});
