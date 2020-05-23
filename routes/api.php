<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});

Route::apiResources(['user' => 'API\UserController']);
Route::get('findUser', 'API\UserController@search')->name('user.search');
Route::get('profile', 'API\ProfileController@index')->name('profile.index');
Route::post('profile', 'API\ProfileController@store')->name('profile.store');
