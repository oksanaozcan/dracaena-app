<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagApiController;
use App\Http\Controllers\API\CategoryApiController;
use App\Http\Controllers\API\BillboardApiController;
use App\Http\Controllers\API\ProductApiController;

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

Route::get('tags', [TagApiController::class, 'index']);
Route::get('categories', [CategoryApiController::class, 'index']);
Route::get('billboards/{id}', [BillboardApiController::class, 'show']);

Route::get('products', [ProductApiController::class, 'index']);

Route::get('products/{id}', [ProductApiController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
