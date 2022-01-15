<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class DemoNoteController extends BaseController
{
  public function getDemoNotes(Request $request) {
    $user = $request->input('user');

    $notes = $user->notes()
      ->orderByDesc('datetime')
      ->get();

    return ['notes' => $notes];
  }


  public function createDemoNote(Request $request) {
    $this->validate($request, [
      'ciphertext' => config('validation.note.ciphertext'),
      'iv' => config('validation.note.iv'),
      'salt' => config('validation.note.salt')
    ]);

    $user = $request->input('user');

    $note = $user->notes()->create([
        'ciphertext' => $request->input('ciphertext'),
        'iv' => $request->input('iv'),
        'salt' => $request->input('salt'),
        'datetime' => time()
      ]);

    return response(['id' => $note->id, 'datetime' => $note->datetime], 201);
  }


  public function editDemoNote(Request $request, $note_id) {
    $this->validate($request, [
      'ciphertext' => config('validation.note.ciphertext'),
      'iv' => config('validation.note.iv'),
      'salt' => config('validation.note.salt')
    ]);
    
    $user = $request->input('user');

    $note = $user->notes()->find($note_id);
    if (!$note)
      return config('response.noteNotFound');
    
    $note->update([
        'ciphertext' => $request->input('ciphertext'),
        'iv' => $request->input('iv'),
        'salt' => $request->input('salt'),
      ]);

    return response(null, 204);
  }


  public function deleteDemoNote(Request $request, $note_id) {
    $user = $request->input('user');

    $note = $user->notes()->find($note_id);
    if (!$note)
      return config('response.noteNotFound');
    
    $note->delete();

    return response(null, 204);
  }
}
