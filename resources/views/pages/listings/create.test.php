<?php

use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

test('details step validates required fields before proceeding', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->call('nextStep', 'location')
        ->assertHasErrors(['title', 'description'])
        ->assertSet('tab', 'details');
});

test('details step passes with valid data', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->assertHasNoErrors()
        ->assertSet('tab', 'location');
});

test('location step validates required fields before proceeding', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->call('nextStep', 'pricing')
        ->assertHasErrors(['address'])
        ->assertSet('tab', 'location');
});

test('location step passes with valid address', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->set('address', '123 Main Street')
        ->call('nextStep', 'pricing')
        ->assertHasNoErrors()
        ->assertSet('tab', 'pricing');
});

test('pricing step validates required fields before proceeding', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->set('address', '123 Main Street')
        ->call('nextStep', 'pricing')
        ->call('nextStep', 'photos')
        ->assertHasErrors(['price'])
        ->assertSet('tab', 'pricing');
});

test('pricing step passes with valid price', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->set('address', '123 Main Street')
        ->call('nextStep', 'pricing')
        ->set('price', '100')
        ->call('nextStep', 'photos')
        ->assertHasNoErrors()
        ->assertSet('tab', 'photos');
});

test('can navigate back to previous tabs without validation', function () {
    Livewire::test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->set('address', '123 Main Street')
        ->call('nextStep', 'pricing')
        ->call('$set', 'tab', 'location')
        ->assertSet('tab', 'location')
        ->call('$set', 'tab', 'details')
        ->assertSet('tab', 'details');
});

test('publish validates all required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::listings.create')
        ->set('tab', 'details')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location')
        ->set('address', '123 Main Street')
        ->call('nextStep', 'pricing')
        ->set('price', '100')
        ->call('nextStep', 'photos')
        ->call('publish')
        ->assertHasNoErrors();
});

test('publish fails without required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::listings.create')
        ->set('tab', 'photos')
        ->call('publish')
        ->assertHasErrors(['title', 'description', 'address', 'price']);
});

test('canAccessTab blocks uncompleted steps', function () {
    $component = Livewire::test('pages::listings.create');

    expect($component->instance()->canAccessTab('details'))->toBeTrue();
    expect($component->instance()->canAccessTab('location'))->toBeFalse();
    expect($component->instance()->canAccessTab('pricing'))->toBeFalse();
    expect($component->instance()->canAccessTab('photos'))->toBeFalse();
});

test('canAccessTab allows completed steps', function () {
    $component = Livewire::test('pages::listings.create')
        ->set('title', 'Valid Listing Title')
        ->set('description', 'This is a valid description with enough characters.')
        ->call('nextStep', 'location');

    expect($component->instance()->canAccessTab('details'))->toBeTrue();
    expect($component->instance()->canAccessTab('location'))->toBeTrue();
    expect($component->instance()->canAccessTab('pricing'))->toBeFalse();
});

test('publish creates listing associated with authenticated user', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::listings.create')
        ->set('title', 'My Listing')
        ->set('description', 'A great listing description')
        ->set('address', '123 Main Street')
        ->set('price', '100')
        ->call('publish')
        ->assertHasNoErrors()
        ->assertRedirect(route('listings.show', Listing::first()));

    $listing = Listing::first();

    expect($listing)->not->toBeNull()
        ->and($listing->user_id)->toBe($user->id)
        ->and($listing->title)->toBe('My Listing')
        ->and($listing->description)->toBe('A great listing description')
        ->and($listing->address)->toBe('123 Main Street')
        ->and($listing->price)->toBe(10000);
});
