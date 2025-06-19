<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::get('/', function () {
//     return Inertia::render('Home');
// })->name('home');

Route::get("{any}", function () {
    return view("app");
})->where("any", ".*");
// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
//
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';

