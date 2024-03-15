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

	const STATUS_TEXT = [
		self::STATUS_PENDING => 'Chờ xử lý',
		self::STATUS_APPROVED => 'Đã chấp nhận',
		self::STATUS_REJECTED => 'Bị từ chối',
		self::STATUS_BORROWED => 'Đã mượn',
		self::STATUS_COMPLETED => 'Đã trả',
	];

	const STATUS_CLASS = [
		self::STATUS_PENDING => '',
		self::STATUS_APPROVED => 'green',
		self::STATUS_REJECTED => 'red',
		self::STATUS_BORROWED => 'orange',
		self::STATUS_COMPLETED => 'blue',
	];

	public function book()
    {
        return $this->hasOne(Books::class, 'book_id', 'book_id');
    }
}
