<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category_id',
        'description',
        'added_by',
        'total',
        'total_active'
    ];

    public $timestamps = false;

	protected $table = 'books';
	protected $primaryKey = 'book_id';

	protected $hidden = array();


    public function bookCategory()
    {
        return $this->hasOne(BookCategories::class, 'id', 'category_id');
    }

    public function issues()
    {
        return $this::count();
    }
}
