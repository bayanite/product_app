<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProductController;
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
//-------------------------------------------------------------------------------------------
Route::prefix("auth")->group(function () {
    Route::post('/register', [AuthController::class, 'createAccount']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});
//-------------------------------------------------------------------------------------------
Route::prefix("category")->group(function () {
    Route::get('/list', [CategoryController::class, 'index']);
    Route::get('/preview/{id}', [CategoryController::class, 'show']);
});
//-------------------------------------------------------------------------------------------
Route::prefix("products")->group(function () {
    Route::get('/list', [ProductController::class, 'index']);
    Route::post('/add', [ProductController::class, 'store'])->middleware('auth:api');
    Route::get('/preview/{product}', [ProductController::class, 'show']);
    Route::post('/edit/{id}', [ProductController::class, 'update'])->middleware('auth:api');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->middleware('auth:api');
    Route::get('/search', [ProductController::class, 'index']);
    Route::get('/sort', [ProductController::class, 'sort']);


    Route::prefix("/comments")->group(function () {
        Route::get('/list/{productId}', [CommentController::class, 'index']);
        Route::post('/add/{productId}', [CommentController::class, 'store'])->middleware('auth:api');
        Route::post('/edit/{idComment}', [CommentController::class, 'update'])->middleware('auth:api');
        Route::delete('/delete/{idComment}', [CommentController::class, 'destroy'])->middleware('auth:api');
    });

    Route::prefix("/likes")->group(function () {
        Route::get('/list/{idProduct}', [LikeController::class, 'index']);
        Route::post('/add/{idProduct}', [LikeController::class, 'store'])->middleware('auth:api');
    });
});
