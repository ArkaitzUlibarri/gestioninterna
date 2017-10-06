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

Route::get('reports', 'Api\v1\ReportController@index');
Route::get('lastreport', 'Api\v1\ReportController@last');
Route::patch('reports/{id}', 'Api\v1\ReportController@update');
Route::post('reports', 'Api\v1\ReportController@store');
Route::delete('reports/{id}', 'Api\v1\ReportController@destroy');

Route::patch('groups/{id}', 'Api\v1\GroupController@update');
Route::post('groups', 'Api\v1\GroupController@store');
Route::get('groups', 'Api\v1\GroupController@index');
Route::delete('groups/{id}', 'Api\v1\GroupController@destroy');

Route::patch('teleworking/{id}', 'Api\v1\TeleworkingController@update');
Route::post('teleworking', 'Api\v1\TeleworkingController@store');
Route::get('teleworking', 'Api\v1\TeleworkingController@index');
Route::delete('teleworking/{id}', 'Api\v1\TeleworkingController@destroy');

Route::patch('reductions/{id}', 'Api\v1\ReductionController@update');
Route::post('reductions', 'Api\v1\ReductionController@store');
Route::get('reductions', 'Api\v1\ReductionController@index');
Route::delete('reductions/{id}', 'Api\v1\ReductionController@destroy');

Route::post('groupsUser', 'Api\v1\GroupUserController@store');
Route::get('groupsUser', 'Api\v1\GroupUserController@index');
Route::delete('groupsUser/{id}', 'Api\v1\GroupUserController@destroy');

Route::post('categories', 'Api\v1\CategoryUserController@store');
Route::get('categories', 'Api\v1\CategoryUserController@index');
Route::delete('categories/{id}', 'Api\v1\CategoryUserController@destroy');

Route::patch('users/{id}', 'Api\v1\UsersController@update');

//Performance Evaluation
Route::get('month_reports','Api\v1\EvaluationPerformanceController@loadMonthReports');
Route::get('project_table','Api\v1\EvaluationPerformanceController@loadProjectTable');