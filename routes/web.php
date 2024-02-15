<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/login', function () {
    return view('Auth.login');
});


Route::get('/', function () {
    return view('index');
});

Route::get('/{pathMatch}', function(){
    return view('index');
})->where('pathMatch', ".*");
