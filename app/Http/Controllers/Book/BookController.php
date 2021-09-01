<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BookModel;
use App\Models\NoteModel;

use Validator;

class BookController extends Controller
{
    public function bookLogin($id, Request $req) {
        $rules = [
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::find($id);

        if (is_null($book)) {
            return response()->json(['status' => false, 'message' => "Книга не найдена"], 200);
        }
        if ($book->password_hash != hash('sha256', $req->password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }

        $result_data = [
            'status' => true
        ];

        return response()->json($result_data, 200);
    }

    public function bookCreate(Request $req) {
        $rules = [
            'title' => 'required|min:3|max:64',
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::create([
            'title' => $req->title,
            'password_hash' => hash('sha256', $req->password_hash)
        ]);

        $result_data = [
            'status' => true,
            'book_id' => $book->id
        ];
        return response()->json($result_data, 200);
    }

    public function bookByTitle($title) {
        $book = BookModel::where('title', $title)->first();
        if (is_null($book)) {
            return response()->json(['status' => false, 'message' => "Такой книги нет"], 200);
        }

        $result_data = [
            'status' => true,
            'book' => [
                'id' => $book->id,
                'title' => $book->title
            ]
        ];
        return response()->json($result_data, 200);
    }

    public function bookChangePassword($book_id, Request $req) {

        $rules = [
            'old_password_hash' => 'required|min:64|max:64',
            'new_password_hash' => 'required|min:64|max:64',
            'notes' => 'required'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::find($book_id);
        if (is_null($book)) {
            return response()->json(['status' => false, 'message' => "Книга не найдена"], 200);
        }
        if ($book->password_hash != hash('sha256', $req->old_password_hash)) {
            return response()->json(['status' => false, 'message' => "Пароль не верный"], 200);
        }

        foreach ($req->notes as $note_data) {
            $note = NoteModel::find($note_data["id"]);

            if ($note->book->id != $book->id) {
                return response()->json(['status' => false, 'message' => "Часть записей из чужой книги. Ваша книга может быть повреждена"], 200);
            }

            $note->update([
                'text' => $note_data["text"]
            ]);
        }

        $book->update([
            'password_hash' => hash('sha256', $req->new_password_hash)
        ]);

        $result_data = [
            'status' => true
        ];

        return response()->json($result_data, 200);
    }
}
