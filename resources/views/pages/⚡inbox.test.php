<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected from inbox', function () {
    $this->get(route('inbox'))->assertRedirect(route('login'));
});

test('authenticated users can view inbox', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::inbox')
        ->assertOk();
});

test('inbox shows empty state when no conversations', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::inbox')
        ->assertSee('No conversations yet');
});

test('inbox shows user conversations as buyer', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::inbox')
        ->assertSee($seller->name);
});

test('inbox shows user conversations as seller', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($seller)
        ->test('pages::inbox')
        ->assertSee($buyer->name);
});

test('inbox shows unread badge for unread messages', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();
    Message::factory()->in($conversation)->from($seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::inbox')
        ->assertSee('1');
});
