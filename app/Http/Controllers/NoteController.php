<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class NoteController extends BaseController
{
  public function getNotes(Request $request) {
    $this->validate($request, [
      'limit' => config('validation.note.limit'),
      'offset' => config('validation.note.offset')
    ]);

    $user = $request->input('user');

    $notes = $user->notes()
      ->limit($request->input('limit') + 1)
      ->offset($request->input('offset'))
      ->orderByDesc('datetime')
      ->get();

    $notes_is_over = count($notes) !== $request->input('limit') + 1;

    return [
      'notes' => $notes,
      'notes_is_over' => $notes_is_over
    ];
  }


  public function getTodayNotes(Request $request) {
    $user = $request->input('user');

    $notes = $user->notes()
      ->where('datetime', '>', time() - 60 * 60 * 24)
      ->where('datetime', '<', time())
      ->orderByDesc('datetime')
      ->get();

    return ['notes' => $notes];
  }


  public function createNote(Request $request) {
    $this->validate($request, [
      'ciphertext' => config('validation.note.ciphertext'),
      'iv' => config('validation.note.iv'),
      'salt' => config('validation.note.salt')
    ]);
    $user = $request->input('user');
    if ($user->username === 'demo')
      return response(['id' => time(), 'datetime' => time()], 201);

    $note = $user->notes()
      ->create([
        'ciphertext' => $request->input('ciphertext'),
        'iv' => $request->input('iv'),
        'salt' => $request->input('salt'),
        'datetime' => time()
      ]);

    return response(['id' => $note->id, 'datetime' => $note->datetime], 201);
  }


  public function editNote(Request $request, $note_id) {
    $this->validate($request, [
      'ciphertext' => config('validation.note.ciphertext'),
      'iv' => config('validation.note.iv'),
      'salt' => config('validation.note.salt')
    ]);
    $user = $request->input('user');

    $note = $user->notes()->find($note_id);
    if (!$note) return config('response.noteNotFound');
    if ($user->username === 'demo') return response(null, 204);
    
    $note->update([
        'ciphertext' => $request->input('ciphertext'),
        'iv' => $request->input('iv'),
        'salt' => $request->input('salt'),
      ]);

    return response(null, 204);
  }


  public function deleteNote(Request $request, $note_id) {
    $user = $request->input('user');

    $note = $user->notes()->find($note_id);
    if (!$note) return config('response.noteNotFound');
    if ($user->username === 'demo') return response(null, 204);
    
    $note->delete();

    return response(null, 204);
  }
}
