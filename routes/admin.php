<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\Admin\ManageAdminControler;
use App\Http\Controllers\Admin\ManageScooters;
use App\Http\Controllers\Seller\ManageSellersController;

Route::middleware(['admin_guest'])->group(function () {
    Route::get('/login', [RegisterController::class, 'getLoginIndex']);
    Route::post('/login', [RegisterController::class, 'login'])->name('admin.login');
});

// seller app apis
Route::post('/login-seller-marager', [RegisterController::class, 'sellersManagerLogin']);
Route::middleware('auth:sanctum')->post('/get-admin', [RegisterController::class, 'getAdmin']);
Route::middleware('auth:sanctum')->post('/get-sellers', [ManageSellersController::class, 'getSellers']);
Route::middleware('auth:sanctum')->post('/reload-seller-points', [ManageSellersController::class, 'reloadPoints']);
Route::middleware('auth:sanctum')->post('/delete-seller', [ManageSellersController::class, 'deleteSeller']);
Route::middleware('auth:sanctum')->post('/search-seller', [ManageSellersController::class, 'search']);
Route::middleware('auth:sanctum')->post('/create-seller', [ManageSellersController::class, 'create']);
Route::middleware('auth:sanctum')->post('/update-seller', [ManageSellersController::class, 'update']);

Route::middleware('auth:admin')->group(function () {
    Route::middleware('admin:Master')->get('/', [AdminHomeController::class, 'getIndex'])->name('admin.home');

    //coupon
    Route::middleware('admin:Master')->post('/coupon/put', [AdminHomeController::class, 'addCoupon'])->name('coupon.put');

    //notification
    Route::middleware('admin:Master')->post('/notification/push', [AdminHomeController::class, 'pushNotificationmain'])->name('notification.push');

    // users
    Route::middleware('admin:Moderator')->prefix('users')->group(function () {
        Route::get('/', [ManageUsersController::class, 'previewIndex'])->name('prev.users');
        Route::post('/', [ManageUsersController::class, 'getUsers'])->name('get.users');
        Route::post('/approve', [ManageUsersController::class, 'approve'])->name('user.approve');
        Route::post('/reject', [ManageUsersController::class, 'reject'])->name('user.reject');
    });

    // admins
    Route::middleware('admin:Master')->prefix('admins')->group(function () {
        Route::get('/', [ManageAdminControler::class, 'index'])->name('admins.manage');
        Route::get('/get', [ManageAdminControler::class, 'get'])->name('get.admins');
        Route::post('/add', [ManageAdminControler::class, 'add'])->name('admin.add');
        Route::post('/update', [ManageAdminControler::class, 'update'])->name('admin.update');
        Route::post('/delete', [ManageAdminControler::class, 'delete'])->name('admin.delete');
    });

    // scooters
    Route::middleware('admin:Technician')->prefix('scooters')->group(function () {
        Route::get('/', [ManageScooters::class, 'index'])->name('scooters.manage');
        Route::get('/zones', [ManageScooters::class, 'zonesIndex'])->name('zones.manage');
        Route::post('/zones-add', [ManageScooters::class, 'add'])->name('zones.add');
        Route::post('/zones-delete', [ManageScooters::class, 'delete'])->name('zones.delete');
    });

    //logout
    Route::get('/logout', [RegisterController::class, 'logout'])->name('admin.logout');
});