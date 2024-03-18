<?php

use App\Http\Controllers\api\BabyController;
use App\Http\Controllers\api\BabyMeasuersController;
use App\Http\Controllers\api\ExcerptController;
use App\Http\Controllers\api\FavoriteController;
use App\Http\Controllers\api\LinkController;
use App\Http\Controllers\api\NameController;
use App\Http\Controllers\api\NotificationsController;
use App\Http\Controllers\api\PregnancyCalculatorController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\Sliders2Controller;
use App\Http\Controllers\api\Sliders3Controller;
use App\Http\Controllers\api\SlidersController;
use App\Http\Controllers\api\WeekController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/update', [ProfileController::class, 'update']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/names', [NameController::class, 'store']);
    Route::get('/names', [NameController::class, 'names']);

    Route::get('/favorites', [FavoriteController::class, 'favorites']);

    Route::post('names/{name}/favorite', [FavoriteController::class, 'favorite']);
    Route::delete('names/{name}/unfavorite', [FavoriteController::class, 'unfavorite']);

    Route::post('/excerpts', [ExcerptController::class, 'store']);
    Route::get('/excerpts', [ExcerptController::class, 'index']);

    Route::get('/weeks', [WeekController::class, 'index']);

    Route::post('/pregnancy-calculation', [PregnancyCalculatorController::class, 'calculate']);
    Route::post('/check-weight', [PregnancyCalculatorController::class, 'checkWeightGain']);

    Route::get('/links', [LinkController::class, 'getAllLinks']);
    Route::post('/add-link', [LinkController::class, 'addLink']);

    Route::post('/check-pregnancy', [\App\Http\Controllers\api\KicksController::class, 'checkPregnancy']);

    Route::post('/compare-weight', [\App\Http\Controllers\api\KicksController::class, 'compareWeight']);

    Route::post('/sliders', [SlidersController::class, 'store'])->name('sliders.store');

    Route::get('/sliders', [SlidersController::class, 'index']);


    Route::post('/sliders2', [Sliders2Controller::class, 'store'])->name('sliders.store');

    Route::get('/sliders2', [Sliders2Controller::class, 'index']);

    Route::post('/sliders3', [Sliders3Controller::class, 'store'])->name('sliders.store');

    Route::get('/sliders3', [Sliders3Controller::class, 'index']);


    Route::post('/check-measuring-sugar', [PregnancyCalculatorController::class, 'checkMeasuringSugar']);


    Route::post('/check-measuring-pressure', [PregnancyCalculatorController::class, 'checkMeasuringPressure']);


    Route::post('babies', [BabyController::class, 'store']);
    Route::get('babies',  [BabyController::class, 'index']);

    Route::post('advices', [\App\Http\Controllers\api\AdviceController::class, 'store']);

    Route::get('advices', [\App\Http\Controllers\api\AdviceController::class, 'index']);


    Route::post('/baby/height', [BabyMeasuersController::class, 'calculateHeightWeight']);

    Route::get('/baby-kicks', [\App\Http\Controllers\api\KicksController::class, 'index']);
    Route::get('/weights', [\App\Http\Controllers\api\PregnancyCalculatorController::class, 'index']);

    Route::get('/sugers', [\App\Http\Controllers\api\PregnancyCalculatorController::class, 'index2']);
    Route::get('/pressures', [\App\Http\Controllers\api\PregnancyCalculatorController::class, 'index3']);



});
Route::get('/notifications', [NotificationsController::class, 'getnotifications']);
Route::post('/send-notification', [NotificationsController::class ,'sendNotification' ]);
