<?php

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

//GET 
Route::get('/Product',[ProductController::class, 'index']);

//GET SHOW
Route::get('/Product/{id}',[ProductController::class, 'show']);

//POST 
Route::post('/Product',[ProductController::class],'store');

//Route::post('product', 'ProductController@store');

//PATCH
Route::patch('/Product/{id}',[ProductController::class],'update');

//DELETE
Route::delete('/Product/{id}',[ProductController::class],'destroy');

