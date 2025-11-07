<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Rute untuk mengelola data rute perjalanan travel
 * Menggunakan soft deletes untuk menjaga data historis
 */
class Rute extends Model
{
    use HasFactory, SoftDeletes;

    // Atribut yang dapat diisi massal untuk rute
    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_estimasi',
        'harga_tiket',
        'status_rute',
        'jam_keberangkatan',
    ];

    // Relasi dengan model Jadwal (satu rute memiliki banyak jadwal)
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Scope untuk filter rute yang aktif
    public function scopeActive($query)
    {
        return $query->where('status_rute', 'aktif');
    }

    // Scope untuk filter rute berdasarkan kota asal
    public function scopeFromCity($query, $city)
    {
        return $query->where('kota_asal', $city);
    }

    // Scope untuk filter rute berdasarkan kota tujuan
    public function scopeToCity($query, $city)
    {
        return $query->where('kota_tujuan', $city);
    }

    // Scope untuk filter rute antara dua kota tertentu
    public function scopeBetweenCities($query, $fromCity, $toCity)
    {
        return $query->where('kota_asal', $fromCity)
            ->where('kota_tujuan', $toCity);
    }
}
