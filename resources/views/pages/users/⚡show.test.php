<?php

use App\Models\User;

test('user bio is displayed on user profile page when available', function () {
    $user = User::factory()->create(['bio' => 'My profile bio']);

    $this->actingAs($user);

    $this->get(route('users.show', $user))
        ->assertSee('My profile bio')
        ->assertDontSee("Hello, I'm {$user->name}");
});

test('fallback text is shown when user has no bio on user profile page', function () {
    $user = User::factory()->create(['bio' => null]);

    $this->actingAs($user);

    $this->get(route('users.show', $user))
        ->assertSee("Hello, I'm {$user->name}");
});

test('fallback text is shown when user has empty bio on user profile page', function () {
    $user = User::factory()->create(['bio' => '']);

    $this->actingAs($user);

    $this->get(route('users.show', $user))
        ->assertSee("Hello, I'm {$user->name}");
});
