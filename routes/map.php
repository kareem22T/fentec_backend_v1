<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get('/nearest-scooter', [MapController::class, 'getNearstScooter']);
Route::get('/scooters', [MapController::class, 'getAllScooters']);