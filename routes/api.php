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
Route::apiResources(['group' => 'API\GroupController']);

Route::apiResources(['news' => 'API\NewsController']);


Route::get('profile', 'API\ProfileController@index')->name('profile.index');
Route::post('profile', 'API\ProfileController@store')->name('profile.store');

Route::get('invoices', 'API\InvoiceController@index')->name('invoices.index');
Route::get('invoices/{invoice}', 'API\InvoiceController@show')->name('invoices.show');

Route::get('flag', 'API\FlagController@index')->name('flag.index');
Route::get('dashboard', 'API\DashboardController@index')->name('dashboard.index');
Route::get('timeline', 'API\TimelineController@index')->name('timeline.index');
