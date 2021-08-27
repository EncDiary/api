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

Route::get('book', 'Book\BookController@book');
Route::get('book/{id}', 'Book\BookController@bookById');

Route::post('book', 'Book\BookController@bookSave');

Route::put('book/{id}', 'Book\BookController@bookEdit');

Route::delete('book/{id}', 'Book\BookController@bookDelete');