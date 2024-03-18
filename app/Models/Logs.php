<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $fillable = array('book_student_id', 'issue_by', 'return_time');

	protected $table = 'book_issue_logs';
	protected $primaryKey = 'id';

	protected $hidden = array();

	public function bookStudent()
    {
        return $this->hasOne(BookStudent::class, 'id', 'book_student_id');
    }

}
