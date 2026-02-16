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

test('address search returns suggestions from Mapbox API', function () {
    Http::fake([
        'api.mapbox.com/*' => Http::response([
            'features' => [
                [
                    'id' => 'test-id-1',
                    'properties' => [
                        'full_address' => '123 Main Street, San Francisco, CA',
                        'name' => '123 Main Street',
                        'coordinates' => [
                            'latitude' => 37.7749,
                            'longitude' => -122.4194,
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    Livewire::test('pages::listings.create')
        ->set('addressSearch', '123 Main St')
        ->assertSet('addressSuggestions', [
            [
                'id' => 'test-id-1',
                'full_address' => '123 Main Street, San Francisco, CA',
                'address' => '123 Main Street',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
            ],
        ]);
});

test('address search does not return suggestions for short input', function () {
    Livewire::test('pages::listings.create')
        ->set('addressSearch', '12')
        ->assertSet('addressSuggestions', []);
});

test('selecting address updates latitude and longitude', function () {
    $suggestions = [
        [
            'id' => 'test-id-1',
            'full_address' => '123 Main Street, San Francisco, CA',
            'address' => '123 Main Street',
            'latitude' => 37.7749,
            'longitude' => -122.4194,
        ],
    ];

    Livewire::test('pages::listings.create')
        ->set('addressSuggestions', $suggestions)
        ->set('selectedAddressId', 'test-id-1')
        ->assertSet('address', '123 Main Street')
        ->assertSet('latitude', 37.7749)
        ->assertSet('longitude', -122.4194);
});

test('publish saves latitude and longitude when address is selected', function () {
    $user = User::factory()->create();

    Http::fake([
        'api.mapbox.com/*' => Http::response([
            'features' => [
                [
                    'id' => 'test-id-1',
                    'properties' => [
                        'full_address' => '123 Main Street, San Francisco, CA',
                        'name' => '123 Main Street',
                        'coordinates' => [
                            'latitude' => 37.7749,
                            'longitude' => -122.4194,
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.create')
        ->set('title', 'My Listing')
        ->set('description', 'A great listing description')
        ->set('addressSearch', '123 Main St')
        ->set('selectedAddressId', 'test-id-1')
        ->set('price', '100')
        ->call('publish')
        ->assertHasNoErrors();

    $listing = Listing::first();

    expect($listing)
        ->latitude->toBe(37.7749)
        ->longitude->toBe(-122.4194);
});
