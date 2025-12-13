<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BorrowingController;

// ADMIN
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('books', BookController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::put('/borrowings/{borrowing}', [BorrowingController::class, 'update']);
});

// MAHASISWA
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::get('/my-borrowings', [BorrowingController::class, 'show']);
});

require __DIR__.'/auth.php';