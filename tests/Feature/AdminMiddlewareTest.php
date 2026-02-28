<?php

use App\Models\User;

test('admin user is detected correctly', function () {
    config(['admin.emails' => ['admin@example.com', 'another-admin@test.com']]);

    $adminUser = User::factory()->create(['email' => 'admin@example.com']);
    $regularUser = User::factory()->create(['email' => 'user@example.com']);
    $adminWithDifferentCase = User::factory()->create(['email' => 'Admin@Example.COM']);

    expect($adminUser->isAdmin())->toBeTrue();
    expect($regularUser->isAdmin())->toBeFalse();
    expect($adminWithDifferentCase->isAdmin())->toBeTrue();
});

test('admin middleware allows access to admin users', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $adminUser = User::factory()->create(['email' => 'admin@example.com']);

    $response = $this->actingAs($adminUser)
        ->get(route('cp.listings.index'));

    $response->assertStatus(200);
});

test('admin middleware blocks access to non-admin users', function () {
    config(['admin.emails' => ['admin@example.com']]);

    $regularUser = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->actingAs($regularUser)
        ->get(route('cp.listings.index'));

    $response->assertStatus(403);
});

test('admin middleware blocks unauthenticated users', function () {
    $response = $this->get(route('cp.listings.index'));

    $response->assertRedirect(route('login'));
});
