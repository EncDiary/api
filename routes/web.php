<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/test', function () use ($router) {
//     return $router->app->version();
// });

use Illuminate\Http\Request;

$router->get('ping', function () {
	return "Pong";
});

$router->post('request', 'UserController@requestOneTimeKey');
$router->post('register', 'UserController@register');
$router->post('auth', 'UserController@auth');
$router->post('delete_account', ['middleware' => 'auth', 'uses' => 'UserController@deleteAccount']);
$router->get('backup', ['middleware' => 'auth', 'uses' => 'UserController@backup']);


$router->get('notes', ['middleware' => 'auth', 'uses' => 'NoteController@getNotes']);
$router->get('notes/today', ['middleware' => 'auth', 'uses' => 'NoteController@getTodayNotes']);
$router->post('note', ['middleware' => 'auth', 'uses' => 'NoteController@createNote']);
$router->put('note/{note_id}', ['middleware' => 'auth', 'uses' => 'NoteController@editNote']);
$router->delete('note/{note_id}', ['middleware' => 'auth', 'uses' => 'NoteController@deleteNote']);





// $router->post('users', 'UserController@getUsers');