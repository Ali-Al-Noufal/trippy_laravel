<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BookingController;
use App\Http\Controllers\api\FlightController;
use App\Http\Controllers\api\MessageController;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/messages',[MessageController::class,'store']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/flights',[FlightController::class,'index']);
    Route::get('/flights/{flight}',[FlightController::class,'show']);
    Route::get('/bookings/{booking}',[BookingController::class,'show']);
    Route::middleware(['role:admin'])->group(function(){
    Route::post('/flights',[FlightController::class,'store']);
    Route::put('/flights/edit/{flight}',[FlightController::class,'update']);
    Route::delete('/flights/{flight}',[FlightController::class,'destroy']);
    Route::get('/messages',[MessageController::class,'index']);
    Route::delete('/messages/{message}',[MessageController::class,'show']);
    Route::get('/bookings',[BookingController::class,'showBookings']);
    Route::put('/bookings/{booking}',[BookingController::class,'update']);
});
Route::middleware(['role:passenger'])->group(function(){
    Route::get('/user/flights',[FlightController::class,'index']);
    Route::get('/user/flights/{flight}',[FlightController::class,'show']);
    Route::get('/user/bookings',[BookingController::class,'index']);
    Route::post('/user/bookings/create/{flight}',[BookingController::class,'store']);
    Route::delete('/user/bookings/{booking}',[BookingController::class,'destroy']);
});
    Route::post('logout',[AuthController::class,'logout']);
});

