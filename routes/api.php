<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TodoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// CRUD routes
Route::get('/todos', [TodoController::class, 'index']);           // GET semua todo
Route::post('/todos', [TodoController::class, 'store']);          // POST buat todo
Route::get('/todos/{id}', [TodoController::class, 'show']);       // GET satu todo
Route::put('/todos/{id}', [TodoController::class, 'update']);     // PUT update todo
Route::delete('/todos/{id}', [TodoController::class, 'destroy']); // DELETE todo

// Ekspor Excel
Route::get('/todos-export', [TodoController::class, 'export']);

// Chart data
Route::get('/chart', [TodoController::class, 'chart']);
