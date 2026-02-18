<?php

use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('listings can be filtered by location bounds', function () {
    $user = User::factory()->create();

    Listing::factory()->create([
        'user_id' => $user->id,
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'title' => 'New York Listing',
    ]);

    Listing::factory()->create([
        'user_id' => $user->id,
        'latitude' => 34.0522,
        'longitude' => -118.2437,
        'title' => 'Los Angeles Listing',
    ]);

    Livewire::test('pages::listings.index')
        ->set('bounds', '40.9,-74.3,40.5,-73.8')
        ->assertSee('New York Listing')
        ->assertDontSee('Los Angeles Listing');
});

test('location search fetches suggestions from mapbox', function () {
    Http::fake([
        'api.mapbox.com/*' => Http::response([
            'features' => [
                [
                    'type' => 'Feature',
                    'id' => 'test-id-1',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [-74.0060, 40.7128],
                    ],
                    'properties' => [
                        'full_address' => 'New York, NY, USA',
                        'name' => 'New York',
                        'coordinates' => [
                            'latitude' => 40.7128,
                            'longitude' => -74.0060,
                        ],
                        'bbox' => [-74.3, 40.5, -73.8, 40.9],
                    ],
                ],
            ],
        ]),
    ]);

    Livewire::test('pages::listings.index')
        ->set('locationSearch', 'New York')
        ->assertSet('locationSuggestions', [
            [
                'id' => 'test-id-1',
                'full_address' => 'New York, NY, USA',
                'address' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'bbox' => [-74.3, 40.5, -73.8, 40.9],
            ],
        ]);
});

test('location search does not return suggestions for short input', function () {
    Livewire::test('pages::listings.index')
        ->set('locationSearch', 'Ne')
        ->assertSet('locationSuggestions', []);
});

test('selecting location sets bounds from bbox', function () {
    $suggestions = [
        [
            'id' => 'test-id-1',
            'full_address' => 'New York, NY, USA',
            'address' => 'New York',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'bbox' => [-74.3, 40.5, -73.8, 40.9],
        ],
    ];

    Livewire::test('pages::listings.index')
        ->set('locationSuggestions', $suggestions)
        ->set('selectedLocationId', 'test-id-1')
        ->assertSet('selectedLocationName', 'New York, NY, USA')
        ->assertSet('bounds', '40.9,-74.3,40.5,-73.8');
});

test('clearing location resets filters', function () {
    Livewire::test('pages::listings.index')
        ->set('selectedLocationId', 'test-id')
        ->set('bounds', '40.9,-74.3,40.5,-73.8')
        ->set('selectedLocationName', 'New York')
        ->set('locationSearch', 'New York')
        ->call('clearLocation')
        ->assertSet('selectedLocationId', null)
        ->assertSet('bounds', null)
        ->assertSet('selectedLocationName', null)
        ->assertSet('locationSearch', '')
        ->assertSet('locationSuggestions', []);
});

test('url parameters persist location filters', function () {
    Livewire::withQueryParams([
        'bounds' => '40.9,-74.3,40.5,-73.8',
        'locationSearch' => 'New York',
        'selectedLocationId' => 'test-id',
    ])->test('pages::listings.index')
        ->assertSet('bounds', '40.9,-74.3,40.5,-73.8')
        ->assertSet('locationSearch', 'New York')
        ->assertSet('selectedLocationId', 'test-id');
});

test('listings outside bounds are not shown', function () {
    $user = User::factory()->create();

    Listing::factory()->create([
        'user_id' => $user->id,
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'title' => 'Inside Bounds',
    ]);

    Listing::factory()->create([
        'user_id' => $user->id,
        'latitude' => 34.0522,
        'longitude' => -118.2437,
        'title' => 'Outside Bounds',
    ]);

    Livewire::test('pages::listings.index')
        ->set('bounds', '40.9,-74.3,40.5,-73.8')
        ->assertSee('Inside Bounds')
        ->assertDontSee('Outside Bounds');
});

test('listings without coordinates are shown when no bounds are set', function () {
    $user = User::factory()->create();

    $listing = Listing::factory()->create([
        'user_id' => $user->id,
        'latitude' => null,
        'longitude' => null,
    ]);

    Livewire::test('pages::listings.index')
        ->assertSee($listing->title);
});

test('location with bbox null does not set bounds', function () {
    $suggestions = [
        [
            'id' => 'test-id-1',
            'full_address' => 'Test Location',
            'address' => 'Test',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'bbox' => null,
        ],
    ];

    Livewire::test('pages::listings.index')
        ->set('locationSuggestions', $suggestions)
        ->set('selectedLocationId', 'test-id-1')
        ->assertSet('selectedLocationName', 'Test Location')
        ->assertSet('bounds', null);
});
