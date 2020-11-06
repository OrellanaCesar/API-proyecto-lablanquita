<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
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


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});*/

Route::group(['prefix' => 'auth'], function () {
	Route::post('login', [AuthController::class,'login']);
	Route::post('signupAdministrador', [AuthController::class,'signupAdministrador']);
	Route::post('signupCliente', [AuthController::class,'signupCliente']);

	Route::group(['middleware' => 'auth:api'], function() {
		Route::get('logout',[AuthController::class,'logout']);
		Route::get('user',[AuthController::class,'user']);
	});
});


Route::group(['prefix' => 'contacto'],function(){
	Route::post('', [MessagesController::class, 'store']);
});

Route::group(['prefix' => 'users'],function(){
	Route::post('dataTable', [UserController::class, 'dataTableUsers']);
});

Route::group(['prefix' => 'brands'], function () {
	Route::get('', [BrandController::class, 'index']);
	Route::get('/{id}', [BrandController::class,'getBrand']);
	Route::get('searchProducts/{id}', [BrandController::class,'searchProducts']);
	Route::post('dataTable',[BrandController::class, 'dataTableBrands']);
	Route::post('create', [BrandController::class, 'store']);
	Route::post('update/{id}', [BrandController::class, 'update']);
	Route::delete('delete/{id}',[BrandController::class, 'destroy']);
	Route::group(['middleware' => 'auth:api'], function () {

	});
});

Route::group(['prefix' => 'products'], function () {
	Route::get('', [ProductController::class, 'index']);
	Route::get('bestSeller', [ProductController::class, 'bestSeller']);
	Route::get('getProductsD', [ProductController::class, 'getProductsD']);
	Route::get('offerDay', [ProductController::class, 'offerDay']);
	Route::get('order/ocupedOfferDay', [ProductController::class, 'ocupedOffer']);
	Route::get('order/ocupedBestSeller', [ProductController::class, 'ocupedBest']);
	Route::get('/{id}',[ProductController::class,'show']);
	Route::post('dataTable',[ProductController::class, 'dataTableProducts'] );
	Route::post('create', [ProductController::class, 'store']);
	Route::post('update/{id}',[ProductController::class , 'update']);
	Route::post('searchProducts',[ProductController::class, 'searchProducts'] );
	Route::delete('delete/{id}',[ProductController::class, 'destroy']);
});

Route::group(['prefix' => 'categories'], function () {
	Route::get('', [CategoriesController::class, 'index']);
	Route::get('searchProducts/{id}', [CategoriesController::class, 'searchProducts']);
	Route::post('dataTable',[CategoriesController::class, 'dataTableCategories']);
	Route::post('create', [CategoriesController::class, 'store']);
	Route::post('update/{id}', [CategoriesController::class, 'update']);
	Route::delete('delete/{id}',[CategoriesController::class, 'destroy']);
	Route::get('show/{id}',[CategoriesController::class, 'show']);
});