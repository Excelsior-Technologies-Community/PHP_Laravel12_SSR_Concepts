<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/', function () {
    return Inertia::render('Home', [
        'title' => 'Laravel 12 SSR Page',
        'message' => 'This data comes from Laravel API via SSR',
    ]);
});

Route::get('/posts', function () {
    return Inertia::render('Posts', [
        'posts' => Post::latest()->get()
    ]);
});