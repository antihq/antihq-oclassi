<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('bio can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->set('bio', 'This is my bio')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->bio)->toEqual('This is my bio');
});

test('bio can be nullable', function () {
    $user = User::factory()->create(['bio' => 'Existing bio']);

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->set('bio', null)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->bio)->toBeNull();
});

test('bio cannot exceed 500 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->set('bio', str_repeat('a', 501))
        ->call('updateProfileInformation');

    $response->assertHasErrors(['bio']);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

test('can upload a profile photo', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('profile.jpg');

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->set('photo', $file)
        ->call('updateProfilePhoto')
        ->assertHasNoErrors();

    expect($user->fresh()->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->fresh()->profile_photo_path);
});

test('can remove a profile photo', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('profile.jpg');
    $path = $file->store('profile-photos', 'public');
    $user->update(['profile_photo_path' => $path]);

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->call('removeProfilePhoto')
        ->assertHasNoErrors();

    expect($user->fresh()->profile_photo_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

test('validates photo file type', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1000);

    Livewire::actingAs($user)
        ->test('pages::settings.profile')
        ->set('photo', $file)
        ->call('updateProfilePhoto')
        ->assertHasErrors(['photo' => 'image']);
});

test('returns null profile photo url when no photo is set', function () {
    $user = User::factory()->create();

    expect($user->profilePhotoUrl())->toBeNull();
});

test('returns correct profile photo url when photo is set', function () {
    $user = User::factory()->create(['profile_photo_path' => 'profile-photos/test.jpg']);

    expect($user->profilePhotoUrl())->toBe(Storage::url('profile-photos/test.jpg'));
});
