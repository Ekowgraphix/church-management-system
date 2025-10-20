<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes for mobile app or external integrations
Route::middleware('auth:sanctum')->group(function () {
    // Add API endpoints as needed
});
