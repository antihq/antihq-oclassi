<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'index')->name('home');

Route::livewire('/listings/create', 'pages::listings.create')->name('listings.create');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
