<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = ['tujuan', 'tanggal', 'jam', 'harga', 'rute_id', 'mobil_id', 'is_active', 'day_offset', 'notes'];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get dynamic tanggal based on day_offset
     * This ensures tanggal always calculated from today + offset
     */
    public function getDynamicTanggalAttribute()
    {
        return \Carbon\Carbon::today()->addDays($this->day_offset ?? 0);
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
