<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return response()->json([
        'status' => 'success',
        'message' => 'first api endpoint, welcome!'
    ]);
});
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/register',[AuthController::class,'register']);
Route::post('/refresh',[AuthController::class,'refresh']);

Route::middleware('jwt.auth')->group(function(){
    Route::get('/profile',[AuthController::class,'profile']);
});