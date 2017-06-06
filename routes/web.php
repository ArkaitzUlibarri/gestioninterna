<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/access', function(){
	return view('access');
});

//Users
Route::resource('users', 'UsersController', ['except' => [
    'destroy'
]]);

Route::get('users/{user}/categories/', 'CategoriesController@edit');
Route::get('users/{user}/groups/', 'GroupsController@editUser');

//Contracts
Route::resource('contracts', 'ContractsController', ['except' => [
    'destroy'
]]);

Route::get('contracts/{contract}/teleworking/', 'TeleworkingController@edit');
Route::get('contracts/{contract}/reductions/', 'ReductionsController@edit');

//Projects
Route::resource('projects', 'ProjectsController', ['except' => [
    'destroy'
]]);

Route::get('groups', 'GroupsController@index');
Route::get('projects/{project}/addgroup/', 'GroupsController@edit');

//Working Reports
Route::get('workingreports', ['as'=> 'workingreports.index','uses'=>'WorkingreportController@index']);
Route::get('workingreports/add/{id}/{date}/',['as'=> 'workingreports.edit','uses'=>'WorkingreportController@edit']);


