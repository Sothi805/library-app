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
        'description',
        'total_copies',
        'available_copies',
        'language',
        'source',
        'added_by',
        'snapshot_added_by',
        'updated_by',
        'snapshot_updated_by',
    ];

    public function borrows()
    {
        return $this->hasMany(BookBorrow::class, 'book_id');
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
