<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteModel extends Model
{
    protected $table = "notes";

    protected $fillable = [
        'id',
        'text',
        'datetime',
        'book_id',
        'is_deleted'
    ];

    public $timestamps = false; 

    public function book()
    {
        return $this->belongsTo('App\Models\BookModel', 'book_id', 'id');
    }
}
