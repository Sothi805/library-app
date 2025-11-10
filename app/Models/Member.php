<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'status',
        'inactive_since',
        'added_by',
        'snapshot_added_by',
        'updated_by',
        'snapshot_updated_by',
    ];

    public function borrows()
    {
        return $this->hasMany(BookBorrow::class, 'member_id');
    }
}
