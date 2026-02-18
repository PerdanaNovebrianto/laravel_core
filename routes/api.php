<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::middleware('api.localization')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    
    // Protected Routes (Require Token)
    Route::middleware('auth:sanctum')->group(function () {
        // Route::put('/update-password', [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
        Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('auth.refreshToken');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
        Route::prefix('user')->group(function () {
            Route::get('/all', [UserController::class, 'all'])->name('user.all')->middleware('ability:user-get');
            Route::get('/detail/{id}', [UserController::class, 'detail'])->name('user.detail')->middleware('hashids', 'ability:user-detail');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('hashids', 'ability:user-update');
            Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware('hashids', 'ability:user-delete');
        });
    });
});
