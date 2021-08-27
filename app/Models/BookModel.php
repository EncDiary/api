<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    protected $table = "books";

    protected $fillable = [
        'id',
        'title',
        'password_hash'
    ];
}
