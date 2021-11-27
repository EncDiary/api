<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends ModelUuid
{
	protected $guarded = [
		'username'
	];

	protected $hidden = [
		'one_time_key', 'one_time_key_expiration_datetime', 'public_key'
	];

	public function notes() {
		return $this->hasMany(Note::class);
	}
}
