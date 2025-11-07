<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    protected $fillable = ['name'];

    public function book() {
        return $this->hasMany(Book::class, 'category_id');
    }
}
