<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::index')->name('home');

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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
