<?php

use Illuminate\Http\Request;

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

Route::get('api/example-get', 'ArticleController@exampleGet');
Route::post('api/example-post', 'ArticleController@examplePost');
Route::put('api/example-put/{id}', 'ArticleController@examplePut');
Route::delete('api/example-delete/{id}', 'ArticleController@exampleDelete');
