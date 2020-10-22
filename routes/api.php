<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
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


Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


Route::group(['prefix' => 'brands'], function () {
	Route::get('', [BrandController::class, 'index']);
	Route::post('dataTable',[BrandController::class, 'dataTableBrands']);
	Route::post('create', [BrandController::class, 'store']);
	Route::post('update/{id}', [BrandController::class, 'update']);
	Route::delete('delete/{id}',[BrandController::class, 'destroy']);
	Route::group(['middleware' => 'auth:api'], function () {

	});
});

Route::group(['prefix' => 'products'], function () {
	Route::get('', [ProductController::class, 'index']);
	Route::get('offerDay', [ProductController::class, 'offerDay']);
	Route::get('bestSeller', [ProductController::class, 'bestSeller']);
});
