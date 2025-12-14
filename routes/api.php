<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\BookReadController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('books', BookController::class);
    Route::apiResource('categories', CategoryController::class);

    // admin hanya monitoring
    Route::get('/borrowings', [BorrowingController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| MAHASISWA
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::get('/my-borrowings', [BorrowingController::class, 'myBorrowings']);

    // mahasiswa yang mengembalikan buku
    Route::post(
        '/borrowings/{borrowing}/return',
        [BorrowingController::class, 'return']
    );

    Route::get('/student/books', [BookController::class, 'publicIndex']);
    Route::get(
        '/books/{book}/read',
        [BookReadController::class, 'read']
    );
});
