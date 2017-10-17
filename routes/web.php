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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/access', function(){
	return view('access');
});

//Users
Route::get('users/{user}/groups/', 'GroupsController@editUser');
Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit']]);

//Contracts
Route::get('contracts/{contract}/teleworking/', 'TeleworkingController@edit');
Route::get('contracts/{contract}/reductions/', 'ReductionsController@edit');
Route::resource('contracts', 'ContractsController');

//Projects
Route::get('groups', 'GroupsController@index');
Route::get('projects/{project}/addgroup/', 'GroupsController@edit');
Route::resource('projects', 'ProjectsController', ['except' => [
    'destroy'
]]);

//Working Reports
Route::get('workingreports', ['as'=> 'workingreports.index','uses'=>'WorkingreportController@index']);
Route::get('workingreports/add/{id}/{date}/',['as'=> 'workingreports.edit','uses'=>'WorkingreportController@edit']);

//Validation
Route::get('validation', 'ValidationController@index');
Route::get('validation/download', 'ValidationController@download');
Route::get('validation/year_report', 'ValidationController@yearReport');

//Holidays
Route::get('holidays','CalendarHolidaysController@index');//Solicitar vacaciones

//Evaluations
Route::get('evaluations','PerformancesController@index');
Route::get('evaluations/download/{year}/{employee}/',['as'=> 'evaluations.download','uses'=> 'PerformancesController@download']);

/**
 * API, mirar autenticacion token o passport en el api.php
 */
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
Route::patch('api/performance-evaluation/{ids}','Api\v1\EvaluationPerformanceController@update');
Route::delete('api/performance-evaluation/{ids}','Api\v1\EvaluationPerformanceController@destroy');