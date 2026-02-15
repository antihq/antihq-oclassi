<?php

use App\Models\Listing;
use App\Models\ListingPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

test('loads existing listing data correctly', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create([
        'title' => 'Test Listing',
        'description' => 'Test description',
        'address' => '123 Test St',
        'address_line_2' => 'Apt 4',
        'price' => 15000,
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->assertSet('title', 'Test Listing')
        ->assertSet('description', 'Test description')
        ->assertSet('address', '123 Test St')
        ->assertSet('addressLine2', 'Apt 4')
        ->assertSet('price', '150');
});

test('non owner cannot edit listing', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $listing = Listing::factory()->for($owner)->create();

    Livewire::actingAs($otherUser)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->assertStatus(403);
});

test('can update listing details', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create([
        'title' => 'Original Title',
        'description' => 'Original description',
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('title', 'Updated Title')
        ->set('description', 'Updated description')
        ->call('save')
        ->assertHasNoErrors();

    expect($listing->fresh())
        ->title->toBe('Updated Title')
        ->description->toBe('Updated description');
});

test('can update location', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create([
        'address' => 'Old Address',
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('address', 'New Address')
        ->set('addressLine2', 'Suite 100')
        ->call('save')
        ->assertHasNoErrors();

    expect($listing->fresh())
        ->address->toBe('New Address')
        ->address_line_2->toBe('Suite 100');
});

test('can update price', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create([
        'price' => 10000,
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('price', '250')
        ->call('save')
        ->assertHasNoErrors();

    expect($listing->fresh()->price)->toBe(25000);
});

test('can remove existing photo', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();
    $photo = $listing->photos()->create([
        'path' => 'listings/'.$listing->id.'/test.jpg',
        'order' => 0,
    ]);

    Storage::disk('public')->put('listings/'.$listing->id.'/test.jpg', 'fake image');

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->call('removeExistingPhoto', $photo->id)
        ->assertSet('existingPhotoIds', []);

    expect(ListingPhoto::find($photo->id))->toBeNull();
    Storage::disk('public')->assertMissing('listings/'.$listing->id.'/test.jpg');
});

test('cannot remove photo from different listing', function () {
    $user = User::factory()->create();
    $listing1 = Listing::factory()->for($user)->create();
    $listing2 = Listing::factory()->for($user)->create();

    $photo1 = $listing1->photos()->create([
        'path' => 'listings/'.$listing1->id.'/test.jpg',
        'order' => 0,
    ]);
    $photo2 = $listing2->photos()->create([
        'path' => 'listings/'.$listing2->id.'/test.jpg',
        'order' => 0,
    ]);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing1])
        ->call('removeExistingPhoto', $photo2->id);

    expect(ListingPhoto::find($photo2->id))->not->toBeNull();
});

test('can navigate between all tabs freely', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('tab', 'location')
        ->assertSet('tab', 'location')
        ->set('tab', 'pricing')
        ->assertSet('tab', 'pricing')
        ->set('tab', 'photos')
        ->assertSet('tab', 'photos')
        ->set('tab', 'details')
        ->assertSet('tab', 'details');
});

test('validates required fields on save', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('title', '')
        ->set('description', '')
        ->set('address', '')
        ->set('price', '')
        ->call('save')
        ->assertHasErrors(['title', 'description', 'address', 'price']);
});

test('price must be numeric and non negative', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('price', '-10')
        ->call('save')
        ->assertHasErrors(['price']);

    Livewire::actingAs($user)
        ->test('pages::listings.edit', ['listing' => $listing])
        ->set('price', 'abc')
        ->call('save')
        ->assertHasErrors(['price']);
});
