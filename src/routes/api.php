<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::get('/users', "App\Http\Controllers\UserController@index")->middleware('auth:api');
Route::post('/users', "App\Http\Controllers\UserController@create")->middleware('auth:api');
Route::get('/users/{id}', "App\Http\Controllers\UserController@show")->middleware('auth:api');
Route::post('/users/{id}', "App\Http\Controllers\UserController@edit")->middleware('auth:api');
Route::delete('/users/{id}', "App\Http\Controllers\UserController@delete")->middleware('auth:api');

Route::get('/roles', "App\Http\Controllers\RolesController@index")->middleware('auth:api');
Route::get('/roles/{id}', "App\Http\Controllers\RolesController@show")->middleware('auth:api');


Route::post('/cms', 'App\Http\Controllers\CmsController@create')->middleware('auth:api');
Route::get('/cms/{id}', 'App\Http\Controllers\CmsController@show')->middleware('auth:api');
Route::post('/cms/{id}', 'App\Http\Controllers\CmsController@edit')->middleware('auth:api');
Route::delete('/cms/{id}', 'App\Http\Controllers\CmsController@delete')->middleware('auth:api');


// PUBLIC ROUTES
Route::get('/cms', 'App\Http\Controllers\CmsController@index');
Route::get('/page/{slug}', 'App\Http\Controllers\RouterController@search');
