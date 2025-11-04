<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mobil extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_polisi',
        'jenis',
        'kapasitas',
        'tahun',
        'merk',
        'status',
    ];

    public function supir()
    {
        return $this->hasOne(Supir::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'tidak aktif');
    }

    public function scopeWithoutSupir($query)
    {
        return $query->doesntHave('supir');
    }

    public function scopeWithSupir($query)
    {
        return $query->has('supir');
    }

    public function getAvailableSeats($jadwalId)
    {
        $bookedSeats = Booking::where('jadwal_id', $jadwalId)
            ->whereIn('status', ['pending', 'setuju'])
            ->count();

        return $this->kapasitas - $bookedSeats;
    }
}
