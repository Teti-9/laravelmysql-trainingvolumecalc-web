<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolumeController;

Route::get('/usuario', [VolumeController::class, 'getCurrentUser']);
Route::get('/allvolume', [VolumeController::class, 'calculateAllVolume']);
Route::get('/onevolume/{musculo}', [VolumeController::class, 'calculateOneVolume']);
Route::post('/create', [VolumeController::class, 'store']);
Route::put('/update/{id}', [VolumeController::class, 'update']);
Route::delete('/delete/{id}', [VolumeController::class, 'destroy']);
