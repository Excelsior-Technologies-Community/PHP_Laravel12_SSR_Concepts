<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Home', [
        'title' => 'Laravel 12 SSR Page',
        'message' => 'This data comes from Laravel API via SSR',
    ]);
});
