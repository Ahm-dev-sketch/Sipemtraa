<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * Model User untuk mengelola data pengguna aplikasi
 * Mewarisi Authenticatable untuk fitur autentikasi Laravel
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Atribut yang dapat diisi massal
    protected $fillable = [
        'name',
        'whatsapp_number',
        'password',
        'role',
    ];

    // Atribut yang disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting atribut untuk tipe data tertentu
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi dengan model Booking (satu user memiliki banyak booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
