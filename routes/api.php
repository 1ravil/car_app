<?php

use App\Http\Controllers\API\CarBrandController;
use App\Http\Controllers\API\CarBrandModelController;
use Illuminate\Support\Facades\Route;

Route::apiResource('car-brands', CarBrandController::class)
    ->parameters(['car-brands' => 'id']); // Явно указываем параметр как 'id'

Route::prefix('car-brands/{brandId}')->group(function() {
    Route::apiResource('models', CarBrandModelController::class)
        ->parameters(['models' => 'modelId']);
});
