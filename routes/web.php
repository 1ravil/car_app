<?php

use Illuminate\Support\Facades\Route;

Route::get('/test123', function() {
    return response()->json(['message' => 'THIS IS TEST ROUTE']);
});

require __DIR__.'/health.php';
