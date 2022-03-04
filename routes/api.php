<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

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
Route::post('/register',[RegisterController::class,'register'])->name('register');
Route::post('/login', [LoginController::class,'login'])->name('login');
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    Route::middleware('is.admin')->group(function(){
        Route::prefix('/admin')->group(function(){
            Route::post('/create',[AdminController::class,'createClass']);
            Route::get('/classes',[AdminController::class,'showAllClasses']);
            Route::get('/{masterClassID}/class',[AdminController::class,'showClass']);
            Route::put('/{masterClassID}/update',[AdminController::class,'updateClass']);
            Route::delete('/{masterClassID}/delete',[AdminController::class,'deleteClass']);
        });
    });
    Route::middleware('is.siswa')->group(function(){
        Route::prefix('/siswa')->group(function(){

        });
    });
});