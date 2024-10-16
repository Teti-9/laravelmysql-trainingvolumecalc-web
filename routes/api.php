<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolumeController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::put('/update/{id}', [VolumeController::class, 'update']);
Route::delete('/delete/{id}', [VolumeController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/allvolume', [VolumeController::class, 'calculateAllVolume']);
    Route::get('/onevolume/{musculo}', [VolumeController::class, 'calculateOneVolume']);
    Route::post('/create', [VolumeController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
