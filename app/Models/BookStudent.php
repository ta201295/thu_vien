<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookStudent extends Model
{
    protected $table = 'book_student';

    protected $fillable = [
		'book_id',
		'category_id',
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
	const STATUS_EXTEND = 5;

	const STATUS_TEXT = [
		self::STATUS_PENDING => 'Chờ xử lý',
		self::STATUS_APPROVED => 'Đã chấp nhận',
		self::STATUS_REJECTED => 'Bị từ chối',
		self::STATUS_BORROWED => 'Đã mượn',
		self::STATUS_COMPLETED => 'Đã trả',
		self::STATUS_EXTEND => 'Xin gia hạn',
	];

	const STATUS_TEXT_ADMIN = [
		self::STATUS_PENDING => 'Mượn mới',
		self::STATUS_EXTEND => 'Xin gia hạn',
	];

	const STATUS_CLASS = [
		self::STATUS_PENDING => 'blue',
		self::STATUS_APPROVED => 'green',
		self::STATUS_REJECTED => 'red',
		self::STATUS_BORROWED => 'orange',
		self::STATUS_COMPLETED => '',
		self::STATUS_EXTEND => 'blue',
	];

	public function book()
    {
        return $this->hasOne(Books::class, 'book_id', 'book_id');
    }

	public function student()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
}
