<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Mobil untuk mengelola data kendaraan travel
 * Menggunakan soft deletes untuk menjaga data historis
 */
class Mobil extends Model
{
    use HasFactory, SoftDeletes;

    // Atribut yang dapat diisi massal untuk mobil
    protected $fillable = [
        'nomor_polisi',
        'jenis',
        'kapasitas',
        'tahun',
        'merk',
        'status',
    ];

    // Relasi dengan model Supir (satu mobil memiliki satu supir)
    public function supir()
    {
        return $this->hasOne(Supir::class);
    }

    // Relasi dengan model Jadwal (satu mobil digunakan untuk banyak jadwal)
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Scope untuk filter mobil yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk filter mobil yang tidak aktif
    public function scopeInactive($query)
    {
        return $query->where('status', 'tidak aktif');
    }

    // Scope untuk filter mobil yang tidak memiliki supir
    public function scopeWithoutSupir($query)
    {
        return $query->doesntHave('supir');
    }

    // Scope untuk filter mobil yang memiliki supir
    public function scopeWithSupir($query)
    {
        return $query->has('supir');
    }

    // Method untuk menghitung kursi tersedia berdasarkan jadwal tertentu
    public function getAvailableSeats($jadwalId)
    {
        $bookedSeats = Booking::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['pending', 'setuju'])
            ->count();

        return $this->kapasitas - $bookedSeats;
    }
}
