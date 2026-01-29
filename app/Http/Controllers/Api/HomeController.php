<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Laravel 12 SSR API',
            'message' => 'This data comes from Laravel API',
        ]);
    }
}
