<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SellerMapController;

Route::get('/nearest-scooter', [MapController::class, 'getNearstScooter']);
Route::get('/scooters', [MapController::class, 'getAllScooters']);
Route::get('/scooter-notify', [MapController::class, 'notifyScooter']);

Route::get('/nearest-seller', [SellerMapController::class, 'getNearestSeller']);
Route::get('/sellers', [SellerMapController::class, 'getAllSellers']);
