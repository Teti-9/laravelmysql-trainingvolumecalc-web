<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolumeController;

Route::get('/', [VolumeController::class, 'index']);
Route::get('/usuario', [VolumeController::class, 'getCurrentUser'])->middleware('auth');
Route::get('/allvolume', [VolumeController::class, 'calculateAllVolume'])->middleware('auth');
Route::get('/onevolume/{musculo}', [VolumeController::class, 'calculateOneVolume'])->middleware('auth');
Route::post('/create', [VolumeController::class, 'store'])->middleware('auth');
Route::put('/update/{id}', [VolumeController::class, 'update'])->middleware('auth');
Route::delete('/delete/{id}', [VolumeController::class, 'destroy'])->middleware('auth');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
