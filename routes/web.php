<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Site.index');
})->name('home');

require __DIR__ . '/admin.php';
