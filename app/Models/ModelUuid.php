<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelUuid extends Model
{
	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->id = Str::uuid()->toString();
		});
	}

	public function getIncrementing()
	{
		return false;
	}

	public function getKeyName()
	{
		return 'id';
	}

	public function getKeyType()
	{
		return 'string';
	}

	public $timestamps = false;
}