<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('v1/login', [LoginController::class, 'login']);
Route::post('v1/register', [LoginController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    //Vendor router
    Route::post('v1/vendors', [VendorController::class, 'register']);

    //Catalog Router
    Route::apiResource('v1/catalogs', CatalogController::class);

    Route::post('v1/logout', [LoginController::class, 'logout']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
