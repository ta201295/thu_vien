<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookStudent extends Model
{
    protected $table = 'book_student';

    protected $fillable = [
		'book_id',
		'student_id',
		'number',
		'status',
		'expired_time'
	];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
	const STATUS_BORROWED = 3;
	const STATUS_COMPLETED = 4;
}
