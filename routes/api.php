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

Route::post('/login', [AuthController::class,'login']);

Route::get('books', [BookController::class, 'index']);
Route::get('books', [BookController::class, 'show']);

Route::middleware('auth:sanctum')->group(function() {

    Route::middleware('role:pustakawan')->group(function(){
        Route::post('books', [BookController::class, 'store']);
        Route::post('books/{book}', [BookController::class, 'update']);
        Route::delete('books/{}book', [BookController::class, 'delete']);

        Route::get('stats/pustakawan', [StatsController::class, 'index']);
        Route::apiResource('copies', CopyController::class);
    });

    Route::post('copies/{copy}/borrow', [LoanController::class, 'borrow']);
    Route::post('loans/{loan}/return', [LoanController::class, 'return']);

    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignments}/submit', [SubmissionController::class,'store']);
    Route::post('submissions/{submission}/grade', [SubmissionController::class,'grade']);

    Route::get('/user', function(Request $req){
        return $req->user()->load('role'); });
});