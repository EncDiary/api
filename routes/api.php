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

Route::post('book/{id}', 'Book\BookController@bookLogin');
Route::post('book', 'Book\BookController@bookCreate');
Route::get('book/{title}', 'Book\BookController@bookByTitle');
Route::post('book/changePassword/{book_id}', 'Book\BookController@bookChangePassword');

Route::post('note', 'Note\NoteController@note');
Route::post('note/create', 'Note\NoteController@noteCreate');
Route::post('note/edit/{id}', 'Note\NoteController@noteEdit');
Route::post('note/delete/{id}', 'Note\NoteController@noteDelete');
