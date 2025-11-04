<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tujuan', 'tanggal', 'jam', 'harga', 'rute_id', 'mobil_id', 'is_active', 'day_offset', 'notes'];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

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

    public function supir()
    {
        return $this->hasOneThrough(
            Supir::class,
            Mobil::class,
            'id',           // Foreign key on mobils table
            'mobil_id',     // Foreign key on supirs table
            'mobil_id',     // Local key on jadwals table
            'id'            // Local key on mobils table
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeAvailableSeats($query)
    {
        return $query->whereHas('mobil')
            ->whereExists(function ($subquery) {
                $subquery->selectRaw('1')
                    ->from('mobils')
                    ->whereColumn('mobils.id', 'jadwals.mobil_id')
                    ->whereRaw('(
                        SELECT COUNT(*) FROM bookings 
                        WHERE bookings.jadwal_id = jadwals.id 
                        AND bookings.status IN (?, ?)
                    ) < mobils.kapasitas', ['pending', 'setuju']);
            });
    }
}
