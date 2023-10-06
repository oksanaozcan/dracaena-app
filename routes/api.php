<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BillboardApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\NavigationApiController;

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

Route::get('navigation', [NavigationApiController::class, 'getCategoryWithFiltersAndTags']);
Route::get('billboards/{id}', [BillboardApiController::class, 'show']);

Route::get('products', [ProductApiController::class, 'index']);

Route::get('products/{id}', [ProductApiController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
