<?php

namespace App\Http\Controllers\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BookModel;

use Validator;

class BookController extends Controller
{
    //
    public function book() {
        return response()->json(BookModel::get(), 200);
    }

    public function bookById($id) {
        $book = BookModel::find($id);
        if (is_null($book)) {
            return response()->json(['error' => true, 'message' => "Книга не найдена"], 404);
        }
        return response()->json($book, 200);
    }

    public function bookSave(Request $req) {
        $rules = [
            'title' => 'required|min:3|max:64',
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::create($req->all());
        return response()->json($book, 201);
    }

    public function bookEdit(Request $req, $id) {
        $rules = [
            'title' => 'required|min:3|max:64',
            'password_hash' => 'required|min:64|max:64'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = BookModel::find($id);
        if (is_null($book)) {
            return response()->json(['error' => true, 'message' => "Книга не найдена"], 404);
        }
        $book->update($req->all());
        return response()->json($book, 200);
    }

    public function bookDelete(Request $req, $id) {
        $book = BookModel::find($id);
        if (is_null($book)) {
            return response()->json(['error' => true, 'message' => "Книга не найдена"], 404);
        }
        $book->delete();
        return response()->json("", 204);
    }
}
