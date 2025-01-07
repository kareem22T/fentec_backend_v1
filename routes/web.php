<?php

use App\Exports\DynamicExport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ZonesController;
use App\Http\Controllers\ScooterController;
use App\Http\Controllers\Seller\RegisterController as SellerRigisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Admin\ManageScooters;
use App\Http\Controllers\SurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

Route::group(['middleware' => ['check_api_password']], function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/register-google', [RegisterController::class, 'regWithGoogle']);
    Route::post('/test-google', [RegisterController::class, 'testAccountExists']);
    Route::post('/login-google', [RegisterController::class, 'loginWithGoogle']);
    Route::middleware('auth:sanctum')->post('/register_2', [RegisterController::class, 'register2']);
    Route::middleware('auth:sanctum')->post('/collect', [RegisterController::class, 'collectPoints']);
    Route::post('/login', [RegisterController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/get-user', [RegisterController::class, 'getUser']);
    Route::middleware('auth:sanctum')->post('/get-invitation-code', [RegisterController::class, 'getOrCreateInvitationCode']);
    Route::post('/get-user-notification', [RegisterController::class, 'getNotification']);
    Route::post('/send-forgot-code', [RegisterController::class, 'sendForgotCode']);
    Route::middleware('auth:sanctum')->post('/user/save-notification-token', [RegisterController::class, 'setNotificationToken']);
    Route::middleware('auth:sanctum')->post('/send-code', [RegisterController::class, 'sendVerfication']);
    Route::middleware('auth:sanctum')->post('/active-account', [RegisterController::class, 'activeAccount']);
    Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
    Route::middleware('auth:sanctum')->post('/change-password', [RegisterController::class, 'changePassword']);
    Route::middleware('auth:sanctum')->post('/change-profile-pic', [RegisterController::class, 'editProfilePic']);
    Route::middleware('auth:sanctum')->post('/edit-email', [RegisterController::class, 'editEmail']);
    Route::middleware('auth:sanctum')->post('/edit-phone', [RegisterController::class, 'editPhone']);
    Route::middleware('auth:sanctum')->post('/seen-approving-msg', [RegisterController::class, 'seenApprovingMsg']);
    Route::middleware('auth:sanctum')->post('/get-charges-history', [RegisterController::class, 'getChargesHistory']);
    Route::middleware('auth:sanctum')->post('/logout', [RegisterController::class, 'logout']);
});

Route::group(['middleware' => ['check_api_password'], 'prefix' => 'sellers'], function () {
    Route::post('/register', [SellerRigisterController::class, 'register']);
    Route::post('/login', [SellerRigisterController::class, 'login']);
    Route::post('/get-client', [SellerRigisterController::class, 'getClient']);
    Route::middleware('auth:sanctum')->post('/get-user', [SellerRigisterController::class, 'getSeller']);
    Route::middleware('auth:sanctum')->get('/get-transactions', [SellerRigisterController::class, 'getTransactionsHistory']);
    Route::middleware('auth:sanctum')->post('/transfer', [SellerRigisterController::class, 'transfer']);
    Route::middleware('auth:sanctum')->post('/change-password', [SellerRigisterController::class, 'changePassword']);
    Route::middleware('auth:sanctum')->post('/edit-email', [SellerRigisterController::class, 'editEmail']);
    Route::middleware('auth:sanctum')->post('/edit-phone', [SellerRigisterController::class, 'editPhone']);
    Route::middleware('auth:sanctum')->post('/logout', [SellerRigisterController::class, 'logout']);
});

Route::middleware('auth:sanctum')->post('/put-survey', [SurveyController::class, 'putSurvey']);
Route::middleware('auth:sanctum')->post("/unlock-scooter", [ScooterController::class, "unlockScooter"]);
Route::middleware('auth:sanctum')->post("/lock-scooter", [ScooterController::class, "lockScooter"]);
Route::middleware('auth:sanctum')->post("/submit-trip", [ScooterController::class, "submitTrpPhoto"]);
Route::middleware('auth:sanctum')->post("/get-trips", [ScooterController::class, "getUserTrips"]);
Route::middleware('auth:sanctum')->post("/get-trips-num", [ScooterController::class, "getUserTripsNum"]);
Route::middleware('auth:sanctum')->post("/use-coupon", [RegisterController::class, "useCoupon"]);
Route::get("/testNot", [ScooterController::class, "sendRealTimeData"]);
Route::get("/get-zones", [ScooterController::class, "getZones"]);
Route::get("/check-zones", [ZonesController::class, "whereIot"]);


Route::get('/', function () {
    return 'welcome';
});
Route::get('export', function (Request $request) {
    $table = $request->input('table');
    $headings = $request->boolean('headings', false);
    $conditions = json_decode($request->input('conditions'));

    Log::info($conditions);

    return Excel::download(
        new DynamicExport($table, $headings, $conditions),
        "{$table}_export.xlsx"
    );
})->name('dynamic.export');
