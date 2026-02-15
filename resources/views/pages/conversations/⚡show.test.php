<?php

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected from conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    $this->get(route('conversations.show', $conversation))->assertRedirect(route('login'));
});

test('non participants cannot view conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $otherUser = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($otherUser)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->assertForbidden();
});

test('buyer can view conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($buyer)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->assertOk()
        ->assertSee($seller->name);
});

test('seller can view conversation', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($seller)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->assertOk()
        ->assertSee($buyer->name);
});

test('conversation shows messages', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();
    $message = Message::factory()->in($conversation)->from($buyer)->create(['body' => 'Hello, I am interested!']);

    Livewire::actingAs($seller)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->assertSee('Hello, I am interested!');
});

test('user can send reply', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($seller)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->set('body', 'Thanks for your inquiry!')
        ->call('send')
        ->assertHasNoErrors();

    expect($conversation->messages()->count())->toBe(1)
        ->and($conversation->messages()->first()->body)->toBe('Thanks for your inquiry!')
        ->and($conversation->messages()->first()->sender_id)->toBe($seller->id);
});

test('reply validation requires body', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();

    Livewire::actingAs($seller)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->set('body', '')
        ->call('send')
        ->assertHasErrors(['body']);
});

test('viewing conversation marks messages as read', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create();
    $message = Message::factory()->in($conversation)->from($seller)->unread()->create();

    expect($message->read_at)->toBeNull();

    Livewire::actingAs($buyer)
        ->test('pages::conversations.show', ['conversation' => $conversation]);

    expect($message->fresh()->read_at)->not->toBeNull();
});

test('reply updates last_message_at', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $conversation = Conversation::factory()->between($buyer, $seller)->create(['last_message_at' => now()->subDay()]);

    $originalTime = $conversation->last_message_at;

    Livewire::actingAs($seller)
        ->test('pages::conversations.show', ['conversation' => $conversation])
        ->set('body', 'New message')
        ->call('send');

    expect($conversation->fresh()->last_message_at->isAfter($originalTime))->toBeTrue();
});
