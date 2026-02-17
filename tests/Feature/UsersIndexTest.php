<?php

use App\Models\Listing;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('cp.users.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can view the users index page', function () {
    $users = User::factory()->count(3)->create();

    $response = $this->actingAs($users->first())
        ->get(route('cp.users.index'));
    $response->assertOk();
});

test('users index page displays user information correctly', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'created_at' => now()->subDays(5),
    ]);

    Listing::factory()->count(3)->for($user)->create();

    $response = $this->actingAs($user)
        ->get(route('cp.users.index'));
    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertSee('3');
});

test('users index page displays pagination', function () {
    User::factory()->count(15)->create();

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('cp.users.index'));
    $response->assertOk();
    $response->assertSee('Next');
});
