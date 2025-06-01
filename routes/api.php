<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('folders', FolderController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/folders/{id}/tags', [FolderController::class, 'addTags']);
    Route::delete('/folders/{id}/tags/{tagId}', [FolderController::class, 'removeTag']);
    Route::apiResource('tags', TagController::class);

    Route::prefix('files')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [FileController::class, 'index']);
        Route::post('/upload', [FileController::class, 'upload']);
        Route::put('/{file}', [FileController::class, 'update']);
        Route::delete('/{file}', [FileController::class, 'destroy']);

        Route::post('/{file}/move', [FileController::class, 'move']);
        Route::post('/{file}/copy', [FileController::class, 'copy']);
        Route::get('/{file}/download', [FileController::class, 'download']);
        Route::post('/{file}/share', [FileController::class, 'share']);
    });
});
