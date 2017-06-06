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
Route::delete('reports/{id}', 'Api\ReportController@destroy');
Route::get('lastreport', 'Api\ReportController@last');

Route::patch('groups/{id}', 'Api\GroupController@update');
Route::post('groups', 'Api\GroupController@store');
Route::get('groups', 'Api\GroupController@index');
Route::delete('groups/{id}', 'Api\GroupController@destroy');

Route::patch('teleworking/{id}', 'Api\TeleworkingApiController@update');
Route::post('teleworking', 'Api\TeleworkingApiController@store');
Route::get('teleworking', 'Api\TeleworkingApiController@index');
Route::delete('teleworking/{id}', 'Api\TeleworkingApiController@destroy');

Route::patch('reductions/{id}', 'Api\ReductionApiController@update');
Route::post('reductions', 'Api\ReductionApiController@store');
Route::get('reductions', 'Api\ReductionApiController@index');
Route::delete('reductions/{id}', 'Api\ReductionApiController@destroy');

Route::post('groupsUser', 'Api\GroupUserApiController@store');
Route::get('groupsUser', 'Api\GroupUserApiController@index');
Route::delete('groupsUser/{id}', 'Api\GroupUserApiController@destroy');


