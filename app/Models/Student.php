<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Student extends Authenticatable
{
    protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password',
		'status'
	];

	public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
