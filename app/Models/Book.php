<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'kategori_id',
        'isbn',
        'stok',
        'gambar',
        'is_validated',
    ];

    protected $casts = [
        'stok' => 'integer',
        'is_validated' => 'boolean',
    ];

    // ===== ACCESSORS =====
    public function getTitleAttribute()
    {
        return $this->attributes['judul'] ?? null;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['judul'] = $value;
    }

    public function getAuthorAttribute()
    {
        return $this->attributes['penulis'] ?? null;
    }

    public function setAuthorAttribute($value)
    {
        $this->attributes['penulis'] = $value;
    }

    public function getPublisherAttribute()
    {
        return $this->attributes['penerbit'] ?? null;
    }

    public function setPublisherAttribute($value)
    {
        $this->attributes['penerbit'] = $value;
    }

    public function getYearAttribute()
    {
        return $this->attributes['tahun'] ?? null;
    }

    public function getStockAttribute()
    {
        return $this->attributes['stok'] ?? 0;
    }

    public function setStockAttribute($value)
    {
        $this->attributes['stok'] = $value;
    }

    public function getCoverAttribute()
    {
        return $this->attributes['gambar'] ?? null;
    }

    public function setCoverAttribute($value)
    {
        $this->attributes['gambar'] = $value;
    }

    public function getCategoryIdAttribute()
    {
        return $this->attributes['kategori_id'] ?? null;
    }

    public function setCategoryIdAttribute($value)
    {
        $this->attributes['kategori_id'] = $value;
    }

    // ===== RELASI =====
    public function category()
{
    return $this->belongsTo(Category::class, 'kategori_id');
}

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id');
    }

    // ===== RATING =====
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}