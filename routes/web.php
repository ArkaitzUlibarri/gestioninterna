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

Route::resource('users', 'UsersController', ['except' => [
    'destroy'
]]);

Route::resource('contracts', 'ContractsController', ['except' => [
    'destroy'
]]);

Route::resource('projects', 'ProjectsController', ['except' => [
    'destroy'
]]);

Route::resource('groups', 'GroupsController', ['only' => [
    'index'
]]);

Route::get('workingreports', 'WorkingreportController@index');
Route::get('workingreports/add/{id}/{date}/',['as'=> 'workingreports.edit','uses'=>'WorkingreportController@edit']);

Route::get('/home', 'HomeController@index')->name('home');
