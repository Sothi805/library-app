<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
    'book_id',
    'title',
    'cover_path',
    'author',
    'category_id',
    'published_year',
    'total_copies',
    'available_copies',
    'language',
    'source',
    'status',
    'user_id',
    'added_date',
    'snapshot_added_by',
    'added_by',
    ];

    public function category() {
        return $this->belongsTo(BookCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }


}
