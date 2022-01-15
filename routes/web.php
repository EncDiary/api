<?php

use Illuminate\Http\Request;

$router->get('ping', function () {
	return "Pong";
});

$router->post('request', 'UserController@requestMessage');
$router->post('register', 'UserController@register');
$router->post('auth', 'UserController@auth');
$router->post('delete_account', ['middleware' => 'auth.user', 'uses' => 'UserController@deleteAccount']);
$router->get('backup', ['middleware' => 'auth.user', 'uses' => 'UserController@backup']);


$router->group(['middleware' => 'auth.user'], function () use ($router) {
	$router->get('notes', 'NoteController@getNotes');
	$router->get('notes/today', 'NoteController@getTodayNotes');
	$router->post('note', 'NoteController@createNote');
	$router->put('note/{note_id}', 'NoteController@editNote');
	$router->delete('note/{note_id}', 'NoteController@deleteNote');
});


$router->group(['prefix' => 'admin', 'middleware' => 'admin.enabled'], function () use ($router) {
	$router->post('auth', 'AdminController@auth');
	$router->group(['middleware' => ['auth.admin', 'demo'], 'prefix' => 'demo'], function () use ($router) {
		$router->get('notes', 'DemoNoteController@getDemoNotes');
		$router->post('note', 'DemoNoteController@createDemoNote');
		$router->put('note/{note_id}', 'DemoNoteController@editDemoNote');
		$router->delete('note/{note_id}', 'DemoNoteController@deleteDemoNote');
	});
});
