<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id', 'book_id', 'tanggal_pinjam',
        'tanggal_kembali', 'tanggal_dikembalikan',
        'status', 'denda', 'bayar_denda', 'token',
    ];

    protected $casts = [
        'tanggal_pinjam'       => 'date',
        'tanggal_kembali'      => 'date',
        'tanggal_dikembalikan' => 'datetime',
        'bayar_denda'          => 'boolean',
        'denda'                => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }

    public function hitungDenda()
    {
        if ($this->tanggal_dikembalikan && $this->tanggal_kembali) {
            $selisih = $this->tanggal_kembali->diffInDays($this->tanggal_dikembalikan, false);
            return $selisih > 0 ? $selisih * 2000 : 0;
        }
        $selisih = now()->diffInDays($this->tanggal_kembali, false);
        return $selisih < 0 ? abs($selisih) * 2000 : 0;
    }
}