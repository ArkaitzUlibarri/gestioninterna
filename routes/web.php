<?php
use Carbon\Carbon;
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

Route::get('/', function(){
	if(!empty(Auth::user())){
		return redirect()->route('workingreports.edit', ['id' => Auth::user()->id, 'date' => Carbon::today()->toDateString()]);
	}
	return redirect('login');
});

Route::get('/access', function(){
    return view('access');
});

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

//Users
Route::get('users/{user}/groups/', 'GroupsController@editUser')->middleware(['auth','checkrole']);
Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit']])->middleware(['auth','checkrole']);

//Contracts
Route::get('contracts/{contract}/teleworking/', 'TeleworkingController@edit')->middleware(['auth','checkrole']);
Route::get('contracts/{contract}/reductions/', 'ReductionsController@edit')->middleware(['auth','checkrole']);
Route::resource('contracts', 'ContractsController')->middleware(['auth','checkrole']);

//Projects
Route::get('groups', 'GroupsController@index')->middleware('auth');
Route::get('projects/{project}/addgroup/', 'GroupsController@edit')->middleware(['auth','checkrole']);
Route::resource('projects', 'ProjectsController')->middleware(['auth','checkrole']);

//Working Reports
Route::get('workingreports', ['as'=> 'workingreports.index','uses'=>'WorkingreportController@index'])->middleware('auth');
Route::get('workingreports/add/{id}/{date}/',['as'=> 'workingreports.edit','uses'=>'WorkingreportController@edit'])->middleware(['auth','checkrole']);

//Validation
Route::get('validation', 'ValidationController@index')->middleware('auth');
Route::get('validation/download', 'ValidationController@download')->middleware('auth');
Route::get('validation/year_report', 'ValidationController@yearReport')->middleware('auth');

//Holidays
Route::get('holidays','CalendarHolidaysController@index')->middleware('auth');//Solicitar vacaciones

//Evaluations
Route::get('evaluations','PerformancesController@index')->middleware('auth');
Route::get('evaluations/download/{year}/{employee}/',['as'=> 'evaluations.download','uses'=> 'PerformancesController@download'])->middleware('auth');

//region API, mirar autenticacion token o passport en el api.php
Route::get('api/users','Api\v1\HolidaysValidationController@loadusers');
Route::get('api/holidays','Api\v1\HolidaysValidationController@loadholidays');
Route::get('api/conflicts','Api\v1\HolidaysValidationController@loadconflicts');
Route::get('api/usergroups','Api\v1\HolidaysValidationController@loadgroups');

Route::get('api/validate','Api\v1\ValidationController@index');
Route::patch('api/validate','Api\v1\ValidationController@update');

Route::get('api/calendar','Api\v1\CalendarController@index');
Route::get('api/calendar/userHolidays','Api\v1\CalendarController@userHolidays');
Route::post('api/calendar', 'Api\v1\CalendarController@store');
Route::delete('api/calendar/{id}', 'Api\v1\CalendarController@destroy');

Route::get('api/employees','Api\v1\EvaluationPerformanceController@loadEmployees');
Route::post('api/performance-evaluation','Api\v1\EvaluationPerformanceController@store');
Route::patch('api/performance-evaluation/{id}','Api\v1\EvaluationPerformanceController@update');
Route::delete('api/performance-evaluation/{id}','Api\v1\EvaluationPerformanceController@destroy');
//endregion