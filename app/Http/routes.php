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

/**
 * Show Link Dashboard
 */
Route::get('/', function () {
    $links = Link::orderBy('created_at', 'asc')->get();

    return view('links', [
        'links' => $links
    ]);
});

/**
 * Add New Link
 */
Route::post('/link', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
        'url' => 'required|max:255',
        'description' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Link;
    $task->name = $request->name;
    $task->url = $request->url;
    $task->description = $request->description;
    $task->save();

    return redirect('/');
});

/**
 * Delete Link
 */
Route::delete('/link/{link}', function (Link $link) {
    $link->delete();

    return redirect('/');
});
