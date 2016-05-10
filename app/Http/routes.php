<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
	'as' => '/',
	'uses' => 'IndexController@index'
]);

Route::get('test/', [
	'as' => 'test',
	'uses' => 'TestController@index'
]);

/*
Route::get('/apps/', [
	'as' => 'apps',
	'uses' => 'AppsController@index'
]);
Route::get('apps/{app}', function($code) {
    return App\Apps::where('code', $code)->get();
});

Route::get('apps', function() {
});

*/
Route::resource('apps', 'AppsController');

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

// Route::group(['middleware' => ['web']], function () {
Route::group(['prefix' => 'api/v1/'], function() {
    Route::resource('tasks', 'TaskController');
    // Route::controller('taskFolders', 'TaskFolderController');
    Route::resource('taskFolders', 'TaskFolderController');
    Route::resource('portals', 'PortalController');
});

Route::controllers([
    // 'taskFolders' => 'TaskFolderController',
	// 'auth' => 'Auth\AuthController'
	'auth' => 'Auth\AuthController',
	// 'apps' => 'AppsController'
]);
