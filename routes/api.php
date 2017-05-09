<?php

use Illuminate\Http\Request;

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

/** 
 * Apis
 */

Route::patch('reports/{id}', 'Api\ReportController@update');
Route::post('reports', 'Api\ReportController@store');
Route::get('reports', 'Api\ReportController@index');
//Route::delete('reports/{id}', 'Api\ReportController@destroy');

Route::get('groups', 'Api\GroupController@index');

