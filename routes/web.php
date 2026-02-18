<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/listings')->name('home');

Route::livewire('/listings', 'pages::listings.index')->name('home');

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

Route::livewire('/cp/listings/', 'pages::cp.listings.index')
    ->middleware(['auth', 'verified'])
    ->name('cp.listings.index');

Route::livewire('/cp/users/', 'pages::cp.users.index')
    ->middleware(['auth', 'verified'])
    ->name('cp.users.index');

Route::livewire('/cp/users/{user}', 'pages::cp.users.show')
    ->middleware(['auth', 'verified'])
    ->name('cp.users.show');

Route::livewire('/cp/users/{user}/edit', 'pages::cp.users.edit')
    ->middleware(['auth', 'verified'])
    ->name('cp.users.edit');

Route::livewire('/cp/conversations/', 'pages::cp.conversations.index')
    ->middleware(['auth', 'verified'])
    ->name('cp.conversations.index');

require __DIR__.'/settings.php';
