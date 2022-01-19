<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends ModelUuid
{
  protected $fillable = [
    'ciphertext', 'datetime', 'iv', 'salt'
  ];
  
  protected $hidden = [
    'user_id', 'time_added'
  ];
  
  public function user() {
    return $this->belongsTo(User::class);
  }
}
