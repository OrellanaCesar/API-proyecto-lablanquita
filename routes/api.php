<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


Route::group(['prefix' => 'brands'], function () {
	Route::get('', 'App\Http\Controllers\BrandController@index');
	Route::post('dataTable', 'App\Http\Controllers\BrandController@dataTableBrands');
	Route::post('create', 'App\Http\Controllers\BrandController@store');
	Route::post('update/{id}', 'App\Http\Controllers\BrandController@update');
	Route::delete('delete/{id}','App\Http\Controllers\BrandController@destroy');
	Route::group(['middleware' => 'auth:api'], function () {

	});
});
