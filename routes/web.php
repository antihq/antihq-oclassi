<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome')->name('home');

Route::livewire('/listings', 'pages::listings.index')->name('listings.index');

Route::livewire('/listings/create', 'pages::listings.create')
    ->middleware(['auth', 'verified'])
    ->name('listings.create');

Route::livewire('/listings/{listing}', 'pages::listings.show')
    ->name('listings.show');

Route::livewire('/listings/{listing}/edit', 'pages::listings.edit')
    ->middleware(['auth', 'verified'])
    ->name('listings.edit');

Route::livewire('/listings/{listing}/conversations/create', 'pages::listings.conversations.create')
    ->middleware(['auth', 'verified'])
    ->name('listings.conversations.create');

Route::livewire('/inbox', 'pages::inbox')
    ->middleware(['auth', 'verified'])
    ->name('inbox');

Route::livewire('/conversations/{conversation}', 'pages::conversations.show')
    ->middleware(['auth', 'verified'])
    ->name('conversations.show');

Route::livewire('user/listings/', 'pages::user.listings')
    ->name('user.listings.index');

Route::livewire('/users/{user}', 'pages::users.show')
    ->name('users.show');

Route::redirect('/dashboard', '/listings')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->prefix('cp')->group(function () {
    Route::redirect('/', '/cp/users')->name('cp');

    Route::livewire('/listings/', 'pages::cp.listings.index')->name('cp.listings.index');
    Route::livewire('/listings/{listing}', 'pages::cp.listings.show')->name('cp.listings.show');
    Route::livewire('/listings/{listing}/edit', 'pages::cp.listings.edit')->name('cp.listings.edit');
    Route::livewire('/users/', 'pages::cp.users.index')->name('cp.users.index');
    Route::livewire('/users/{user}', 'pages::cp.users.show')->name('cp.users.show');
    Route::livewire('/users/{user}/edit', 'pages::cp.users.edit')->name('cp.users.edit');
    Route::livewire('/conversations/', 'pages::cp.conversations.index')->name('cp.conversations.index');
    Route::livewire('/conversations/{conversation}', 'pages::cp.conversations.show')->name('cp.conversations.show');
});

require __DIR__.'/settings.php';
