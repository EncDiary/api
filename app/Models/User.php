<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends ModelUuid
{
	protected $guarded = [
		'username'
	];

	protected $hidden = [
		'message', 'message_exp', 'public_key', 'is_admin'
	];

	public function notes() {
		return $this->hasMany(Note::class);
	}

	public static function boot() {
		parent::boot();

		static::deleting(function($user) {
			$user->notes()->delete();
		});
	}
}
