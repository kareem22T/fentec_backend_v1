<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get('/calc_distance', [MapController::class, 'getNearstScooter']);