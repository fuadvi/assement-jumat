<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->apiResource('product', ProductController::class);
Route::middleware('auth:sanctum')->post('transaction', [TransactionController::class, 'checkout']);

Route::middleware('auth:sanctum')->controller(TransactionController::class)->group(function () {
    Route::get('/list-transaction', 'listTransaction');
    Route::post('/transaction', 'Transaction');
    Route::put('/transaction/{id}', 'updateTransaction');
    Route::get('/transaction/{id}', 'show');
});
