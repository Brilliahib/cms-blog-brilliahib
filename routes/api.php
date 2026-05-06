<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectNoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Authenticated user routes
    Route::prefix('auth')->group(function () {
        Route::get('/get-auth', [AuthController::class, 'getAuth']);
        Route::put('/update-account', [AuthController::class, 'updateAccount']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/update-profile-picture', [AuthController::class, 'changeProfilePicture']);
    });

    // Project routes
    Route::apiResource('projects', ProjectController::class);

    // Project notes routes
    Route::prefix('projects/{project}')->group(function () {
        Route::get('notes', [ProjectNoteController::class, 'index']);
        Route::post('notes', [ProjectNoteController::class, 'store']);
        Route::get('notes/{note}', [ProjectNoteController::class, 'show']);
        Route::put('notes/{note}', [ProjectNoteController::class, 'update']);
        Route::delete('notes/{note}', [ProjectNoteController::class, 'destroy']);
    });
});

// Blog routes
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/latest', [BlogController::class, 'latestBlog']);
    Route::get('/search', [BlogController::class, 'searchBlog']);
    Route::get('/{slug}', [BlogController::class, 'show']);
    Route::get('/category/{categoryId}', [BlogController::class, 'byCategory']);
});
