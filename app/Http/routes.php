<?php

use App\Link;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Route::get('/', function () {
	return view('links');
}); */
Route::get('/', 'LinkController@index');

Route::get('/links', 'LinkController@index');
Route::post('/link', 'LinkController@store');
Route::delete('/link/{link}', 'LinkController@destroy');

Route::auth();
