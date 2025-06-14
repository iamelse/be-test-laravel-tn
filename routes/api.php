<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TodoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('todos')->group(function () {
    Route::post('/', [TodoController::class, 'store']);
    Route::get('/export', [TodoController::class, 'export']);
    Route::get('/chart', [TodoController::class, 'chart']);

    // CRUD routes
    Route::get('/{id}', [TodoController::class, 'show']);
    Route::put('/{id}', [TodoController::class, 'update']);
    Route::delete('/{id}', [TodoController::class, 'destroy']);
});
