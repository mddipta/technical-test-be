<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    //Vendor router
    Route::post('v1/vendors', [VendorController::class, 'register']);

    //Catalog Router
    Route::apiResource('v1/catalogs', CatalogController::class);

    Route::post('v1/logout', [AuthController::class, 'logout']);
});
