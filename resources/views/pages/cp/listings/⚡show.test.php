<?php

use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->listing = Listing::factory()->for($this->user)->create();
});

test('redirects unauthenticated users', function () {
    $response = $this->get(route('cp.listings.show', $this->listing));

    $response->assertRedirect(route('login'));
});

test('displays listing show page to authenticated users', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $this->listing])
        ->assertStatus(200);
});

test('displays listing details', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $this->listing])
        ->assertSee($this->listing->title)
        ->assertSee($this->listing->address);
});

test('displays price in correct format', function () {
    $listing = Listing::factory()->for($this->user)->create(['price' => 15000]);

    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $listing])
        ->assertSee('$15,000');
});

test('displays owner name', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $this->listing])
        ->assertSee($this->user->name);
});

test('displays open status for open listings', function () {
    $listing = Listing::factory()->for($this->user)->create(['closed_at' => null]);

    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $listing])
        ->assertSee('Open');
});

test('displays closed status for closed listings', function () {
    $listing = Listing::factory()->for($this->user)->closed()->create();

    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.show', ['listing' => $listing])
        ->assertSee('Closed');
});
