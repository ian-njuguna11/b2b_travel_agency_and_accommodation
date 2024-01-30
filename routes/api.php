<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::put('accommodation/update/{accommodation}', [AccommodationController::class,'update']);
Route::delete('destroy/accommodation/{accommodation}', [AccommodationController::class,'destroy']);
Route::post('accommodations', [AccommodationController::class,'store']);
Route::get('accommodation/list', [AccommodationController::class,'index']);
