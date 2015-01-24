<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::resource('users','UsersController',['except' => ['index','create', 'edit']]);

Route::post('auth/login', 'UsersController@login'); // Post Login
Route::any('auth/logout', 'UsersController@logout');

Route::group(array('before' => 'auth'), function()
{
    Route::resource('post','PostController',['except' => ['create', 'edit']]);
    Route::resource('post.upvote', 'UpvotesController',['except' => ['create','edit','update','show']]);
    Route::resource('post.downvote', 'DownvotesController',['except' => ['create','edit','update','show']]);
});