<?php

use App\Models\Conversation;
use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected from send inquiry', function () {
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    $this->get(route('send-inquiry', $listing))->assertRedirect(route('login'));
});

test('authenticated users can send inquiry', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->assertOk()
        ->assertSee("Send an inquiry to {$seller->name}");
});

test('send inquiry creates conversation and first message', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->set('body', 'I am interested in this item!')
        ->call('send')
        ->assertHasNoErrors()
        ->assertRedirect();

    $conversation = Conversation::where('listing_id', $listing->id)->first();

    expect($conversation)->not->toBeNull()
        ->and($conversation->buyer_id)->toBe($buyer->id)
        ->and($conversation->seller_id)->toBe($seller->id)
        ->and($conversation->messages()->count())->toBe(1)
        ->and($conversation->messages()->first()->body)->toBe('I am interested in this item!');
});

test('send inquiry reuses existing conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();
    $existingConversation = Conversation::factory()->forListing($listing)->between($buyer, $seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->set('body', 'Follow up question')
        ->call('send');

    expect(Conversation::where('listing_id', $listing->id)->count())->toBe(1)
        ->and($existingConversation->fresh()->messages()->count())->toBe(1);
});

test('inquiry body is required', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->set('body', '')
        ->call('send')
        ->assertHasErrors(['body']);
});

test('inquiry body has max length', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->set('body', str_repeat('a', 5001))
        ->call('send')
        ->assertHasErrors(['body']);
});

test('send inquiry redirects to conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $listing = Listing::factory()->for($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::send-inquiry', ['listing' => $listing])
        ->set('body', 'Hello!')
        ->call('send')
        ->assertRedirect(route('conversations.show', Conversation::first()));
});
