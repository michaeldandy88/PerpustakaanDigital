<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CopyController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\AuthController;

/*
|------------------------------------------------------------
| API Routes
|------------------------------------------------------------
|
| Note: pastikan nama parameter route sesuai (singular) agar
| route-model-binding Laravel bekerja dengan baik.
|
*/

Route::post('/login', [AuthController::class, 'login']);

// Public book routes
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('role:pustakawan')->group(function () {
        Route::post('books', [BookController::class, 'store']);
        Route::put('books/{book}', [BookController::class, 'update']);     // PUT untuk update
        Route::delete('books/{book}', [BookController::class, 'destroy']); // destroy, path fixed

        Route::get('stats/pustakawan', [StatsController::class, 'index']);
        Route::apiResource('copies', CopyController::class);
    });

    Route::post('copies/{copy}/borrow', [LoanController::class, 'borrow']);
    Route::post('loans/{loan}/return', [LoanController::class, 'return']);

    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/submit', [SubmissionController::class, 'store']);
    Route::post('submissions/{submission}/grade', [SubmissionController::class, 'grade']);

    Route::get('/user', function (Request $req) {
        return $req->user()->load('role');
    });
});
