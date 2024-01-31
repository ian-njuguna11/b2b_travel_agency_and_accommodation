<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\UsersController;
use App\Models\Accommodation;
use App\Models\Contract;
use App\Models\User;
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

Route::get('users/list', [UsersController::class, 'index']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//dashboard
Route::get('accommodation/count', function(){
    return count(Accommodation::all());
});

Route::get('contract/count', function(){
    return count(Contract::all());
});

Route::get('travel_agents/count', function(User $user){

    return count($user->all());

});

// contracts
Route::post('contract', [ContractController::class, 'store']);
Route::get('contract/list', [ContractController::class,'index']);
Route::put('contract/update/{contract}', [ContractController::class,'update']);
Route::delete('destroy/contract/{contract}', [ContractController::class,'destroy']);
//

//accommodations
Route::put('accommodation/update/{accommodation}', [AccommodationController::class,'update']);
Route::delete('destroy/accommodation/{accommodation}', [AccommodationController::class,'destroy']);
Route::post('accommodations', [AccommodationController::class,'store']);
Route::get('accommodation/list', [AccommodationController::class,'index']);
