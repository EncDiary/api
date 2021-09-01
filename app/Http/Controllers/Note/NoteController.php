<?php

namespace App\Http\Controllers\Note;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\NoteModel;
use App\Models\BookModel;

use Validator;

class NoteController extends Controller
{
    public function note(Request $req) {
        $rules = [
            'password_hash' => 'required|min:64|max:64',
            'book_id' => 'required'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::find($req->book_id);
        if (is_null($book)) {
            return response()->json(['status' => false, 'message' => "Книга не найдена"], 200);
        }
        if ($book->password_hash != hash('sha256', $req->password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }

        if (is_null($req->offset) || is_null($req->limit)) {
            $notes = NoteModel::where('book_id', $req->book_id)
                            ->where('is_deleted', false)
                            ->get();
        } else {
            $notes = NoteModel::where('book_id', $req->book_id)
                        ->where('is_deleted', false)
                        ->orderByDesc('datetime')
                        ->offset($req->offset)
                        ->limit($req->limit)
                        ->get(['id', 'text', 'datetime']);
        }

        $result_data = [
            'status' => true,
            'notes' => $notes
        ];

        return response()->json($result_data, 200);
    }

    public function noteCreate(Request $req) {
        $rules = [
            'password_hash' => 'required|min:64|max:64',
            'text' => 'required|min:1',
            'book_id' => 'required'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::find($req->book_id);
        if (is_null($book)) {
            return response()->json(['status' => false, 'message' => "Книга не найдена"], 200);
        }
        if ($book->password_hash != hash('sha256', $req->password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }

        $note = NoteModel::create([
            'text' => $req->text,
            'datetime' => time(),
            'book_id' => $req->book_id,
        ]);
        $result_data = [
            'status' => true,
            'note' => [
                'id' => $note->id,
                'datetime' => $note->datetime
            ]
        ];

        return response()->json($result_data, 200);
    }

    public function noteEdit(Request $req, $id) {
        $rules = [
            'text' => 'required|min:1',
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note = NoteModel::find($id);
        if (is_null($note)) {
            return response()->json(['status' => false, 'message' => "Запись не найдена"], 200);
        }
        if ($note->book->password_hash != hash('sha256', $req->password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }

        $note->update([
            'text' => $req->text
        ]);
        $result_data = [
            'status' => true
        ];
        return response()->json($result_data, 200);
    }

    public function noteDelete(Request $req, $id) {
        $rules = [
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $note = NoteModel::find($id);
        if (is_null($note)) {
            return response()->json(['status' => false, 'message' => "Запись не найдена"], 200);
        }

        if ($note->book->password_hash != hash('sha256', $req->password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }
        $note->update(['is_deleted' => true]);

        $result_data = [
            'status' => true
        ];

        return response()->json($result_data, 200);
    }
}
