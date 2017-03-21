<?php
//	/*
//	|--------------------------------------------------------------------------
//	| Application Routes
//	|--------------------------------------------------------------------------
//	|
//	| Here is where you can register all of the routes for an application.
//	| It's a breeze. Simply tell Laravel the URIs it should respond to
//	| and give it the controller to call when that URI is requested.
//	|
//	*/
//
//	/*******************************/
//	/**         LOGIN            **/
//	/*****************************/
//
//	Route::get('auth/login', 'Auth\AuthController@getLogin');
//	Route::post('auth/login', ['as' =>'auth/login', 'uses' => 'Auth\AuthController@postLogin']);
//	Route::get('auth/logout', ['as' => 'auth/logout', 'uses' => 'Auth\AuthController@getLogout']);
//	// Registration routes...
//	Route::get('auth/register', 'Auth\AuthController@getRegister');
//	Route::post('auth/register', ['as' => 'auth/register', 'uses' => 'Auth\AuthController@postRegister']);
//
//
//	// Password reset routes...
//	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
//	Route::post('password/reset', 'Auth\PasswordController@postReset');
//
//	// Password reset link request routes...
//	Route::get('password/email', 'Auth\PasswordController@getEmail');
//	Route::post('password/email', 'Auth\PasswordController@postEmail');
//
//	/*******************************/
//	Route::group(['middleware' => 'auth'], function(){
//
//
//		Route::get('/', function () {
//			return view('welcome');
//		});
//
//		Route::get('/home', function () {
//			return view('welcome');
//		});
//
//	});


/*******************************/
/**         LOGIN            **/
/*****************************/

// rutas de logeo
Route::get('/login', 'Login\LoginController@index')->name('login');
Route::post('/login', 'Login\LoginController@verificaCredenciales')->name('login');

// rutas de reseteo de contraseña
Route::get('password/email', 'Login\ClaveController@index');
Route::post('password/email', 'Login\ClaveController@postEmail');

Route::get('password/reset', 'Login\ClaveController@reset');


route::get('/logout', 'Login\LoginController@logout')->name('logout');

/*******************************/




Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('welcome');
});






