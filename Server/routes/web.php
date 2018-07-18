<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('api/example-get', 'ApiController@exampleGet');
Route::post('api/example-post', 'ApiController@examplePost');
Route::put('api/example-put/{id}', 'ApiController@examplePut');
Route::delete('api/example-delete/{id}', 'ApiController@exampleDelete');


Route::get('api/get-card', 'ApiController@getCard');