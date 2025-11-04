<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_estimasi',
        'harga_tiket',
        'status_rute',
        'jam_keberangkatan',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status_rute', 'aktif');
    }

    public function scopeFromCity($query, $city)
    {
        return $query->where('kota_asal', $city);
    }

    public function scopeToCity($query, $city)
    {
        return $query->where('kota_tujuan', $city);
    }

    public function scopeBetweenCities($query, $fromCity, $toCity)
    {
        return $query->where('kota_asal', $fromCity)
            ->where('kota_tujuan', $toCity);
    }
}
