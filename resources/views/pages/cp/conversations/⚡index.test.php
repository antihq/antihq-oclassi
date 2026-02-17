<?php

use App\Models\Conversation;
use App\Models\ListingPhoto;
use App\Models\User;
use Livewire\Livewire;

test('only authenticated user conversations are shown', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $conversation1 = Conversation::factory()
        ->between($user1, $user2)
        ->create(['last_message_at' => now()]);

    $conversation2 = Conversation::factory()
        ->between($user2, $user3)
        ->create(['last_message_at' => now()->subHour()]);

    $this->actingAs($user1);

    $this->get('/cp/conversations/')
        ->assertSee($conversation1->listing->title)
        ->assertDontSee($conversation2->listing->title);
});

test('conversations table displays listing photo and title', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    $conversation = Conversation::factory()
        ->between($buyer, $seller)
        ->create(['last_message_at' => now()]);

    ListingPhoto::create([
        'listing_id' => $conversation->listing_id,
        'path' => 'test-photo.jpg',
        'order' => 1,
    ]);

    $this->actingAs($buyer);

    $this->get('/cp/conversations/')
        ->assertSee($conversation->listing->title)
        ->assertSee($buyer->name);
});

test('conversations are ordered by last_message_at', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $oldConversation = Conversation::factory()
        ->between($user, $user2)
        ->create(['last_message_at' => now()->subDay()]);

    $newConversation = Conversation::factory()
        ->between($user, $user3)
        ->create(['last_message_at' => now()]);

    Livewire::actingAs($user)
        ->test('pages::cp.conversations.index')
        ->assertSeeInOrder([
            $newConversation->listing->title,
            $oldConversation->listing->title,
        ]);
});
