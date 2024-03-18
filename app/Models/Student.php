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

	protected $appends = ['full_name'];

	public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";;
    }

	public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
