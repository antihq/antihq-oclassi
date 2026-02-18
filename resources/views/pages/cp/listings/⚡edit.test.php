<?php

use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->listing = Listing::factory()->for($this->user)->create([
        'title' => 'Original Title',
        'description' => 'Original description',
        'address' => '123 Original St',
        'address_line_2' => 'Apt 1',
        'price' => 15000,
    ]);
});

test('redirects unauthenticated users', function () {
    $response = $this->get(route('cp.listings.edit', $this->listing));

    $response->assertRedirect(route('login'));
});

test('displays listing edit page to authenticated users', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->assertStatus(200);
});

test('loads existing listing data correctly', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->assertSet('title', 'Original Title')
        ->assertSet('description', 'Original description')
        ->assertSet('address', '123 Original St')
        ->assertSet('addressLine2', 'Apt 1')
        ->assertSet('price', '150');
});

test('updates a listing', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('title', 'Updated Title')
        ->set('description', 'Updated Description')
        ->set('address', '123 Updated St')
        ->set('addressLine2', 'Suite 100')
        ->set('price', '250')
        ->call('update')
        ->assertRedirect(route('cp.listings.show', $this->listing));

    expect($this->listing->fresh()->title)->toBe('Updated Title');
    expect($this->listing->fresh()->description)->toBe('Updated Description');
    expect($this->listing->fresh()->address)->toBe('123 Updated St');
    expect($this->listing->fresh()->address_line_2)->toBe('Suite 100');
    expect($this->listing->fresh()->price)->toBe(25000);
});

test('validates required fields', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('title', '')
        ->set('description', '')
        ->set('address', '')
        ->set('price', '')
        ->call('update')
        ->assertHasErrors([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'price' => 'required',
        ]);
});

test('validates title max length', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('title', str_repeat('a', 256))
        ->call('update')
        ->assertHasErrors(['title' => 'max']);
});

test('validates address max length', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('address', str_repeat('a', 256))
        ->call('update')
        ->assertHasErrors(['address' => 'max']);
});

test('validates address_line_2 max length', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('addressLine2', str_repeat('a', 256))
        ->call('update')
        ->assertHasErrors(['addressLine2' => 'max']);
});

test('validates price must be numeric', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('price', 'not-a-number')
        ->call('update')
        ->assertHasErrors(['price' => 'numeric']);
});

test('validates price must be non-negative', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('price', '-10')
        ->call('update')
        ->assertHasErrors(['price' => 'min']);
});

test('allows empty address_line_2', function () {
    Livewire::actingAs($this->user)
        ->test('pages::cp.listings.edit', ['listing' => $this->listing])
        ->set('addressLine2', '')
        ->set('title', 'Updated Title')
        ->set('description', 'Updated description')
        ->set('address', '123 Updated St')
        ->set('price', '200')
        ->call('update')
        ->assertHasNoErrors();

    expect($this->listing->fresh()->address_line_2)->toBeNull();
});
