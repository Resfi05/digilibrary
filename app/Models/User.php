<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'address', 'is_active', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function peminjaman() { return $this->hasMany(Peminjaman::class); }
    public function reviews()    { return $this->hasMany(Review::class); }
    public function favorites()  { return $this->hasMany(Favorite::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
}