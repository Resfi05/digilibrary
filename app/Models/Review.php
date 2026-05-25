<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'book_id', 'rating', 'komentar',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'rating'     => 'integer',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
}