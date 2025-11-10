<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookBorrow extends Model
{
    protected $fillable = [
        'member_id',
        'book_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'condition_before',
        'condition_after',
        'status',
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

