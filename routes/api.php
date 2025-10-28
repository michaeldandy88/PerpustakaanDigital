<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\AuthController;
Route::get('/ping', fn() => 'pong');

Route::post('/login', [AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('books', BookController::class)->only(['index','show','store','update','destroy']);
    Route::post('copies/{copy}/borrow', [LoanController::class,'borrow']);
    Route::post('loans/{loan}/return', [LoanController::class,'return']);
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/submit', [SubmissionController::class,'store']);
    Route::post('submissions/{submission}/grade', [SubmissionController::class,'grade']);
    Route::get('/user', function(Request $req){ return $req->user()->load('role'); });
});