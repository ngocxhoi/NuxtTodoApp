<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['throttle:6,1'])->name('verification.send');

    // Route::post('/logout', [UserController::class, 'logout']);
    // Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);
    Route::get('/project/{project}', [ProjectController::class, 'show']);
    Route::put('/project/{project}', [ProjectController::class, 'update']);
    Route::delete('/project/{project}', [ProjectController::class, 'destroy']);

    // Route::get('/projects/{project}/boards', [BoardController::class, 'index']);
    Route::post('/board', [BoardController::class, 'store']);
    Route::get('/board/{board}', [BoardController::class, 'show']);
    Route::put('/board/{board}', [BoardController::class, 'update']);
    Route::delete('/board/{board}', [BoardController::class, 'destroy']);

    // Route::get('/boards/{board}/ticket', [TicketController::class, 'index']);
    Route::post('/ticket', [TicketController::class, 'store']);
    Route::get('//ticket/{ticket}', [TicketController::class, 'show']);
    Route::put('/ticket/{ticket}', [TicketController::class, 'update']);
    Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy']);
    Route::post('/ticket/{ticket}/assign', [TicketController::class, 'assign']);
    Route::post('/ticket/{ticket}/move', [TicketController::class, 'move']);

    Route::get('/check-user', [UserController::class, 'checkUser']);

});

Route::get('/all-users', [UserController::class, 'allUsers']);

