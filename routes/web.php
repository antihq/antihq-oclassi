<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'index')->name('home');

Route::livewire('/listings/create', 'pages::listings.create')
    ->middleware(['auth', 'verified'])
    ->name('listings.create');

Route::livewire('/listings/{listing}', 'pages::listings.show')
    ->name('listings.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
