<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Jadwal untuk mengelola jadwal perjalanan travel
 * Menggunakan soft deletes untuk menjaga data historis
 */
class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    // Atribut yang dapat diisi massal untuk jadwal
    protected $fillable = ['tujuan', 'jam', 'harga', 'rute_id', 'mobil_id', 'is_active', 'hari_keberangkatan', 'notes'];

    // Casting atribut untuk tipe data tertentu
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Method untuk mendapatkan tanggal-tanggal mendatang berdasarkan hari_keberangkatan
    public function getUpcomingDates($weeksAhead = 4)
    {
        $dates = collect();
        $dayMap = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 0,
        ];

        $targetDay = $dayMap[$this->hari_keberangkatan] ?? null;
        if ($targetDay === null) return $dates;

        $startDate = \Carbon\Carbon::today();
        $endDate = $startDate->copy()->addWeeks($weeksAhead);

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if ($currentDate->dayOfWeek === $targetDay) {
                $dates->push($currentDate->copy());
            }
            $currentDate->addDay();
        }

        return $dates;
    }

    // Relasi dengan model Rute (satu jadwal milik satu rute)
    public function rute()
    {
        return $this->belongsTo(Rute::class);
    }

    // Relasi dengan model Mobil (satu jadwal menggunakan satu mobil)
    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    // Relasi dengan model Booking (satu jadwal memiliki banyak booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Relasi hasOneThrough untuk mendapatkan supir melalui mobil
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

    // Scope untuk filter jadwal yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter jadwal berdasarkan hari keberangkatan
    public function scopeForDay($query, $day)
    {
        return $query->where('hari_keberangkatan', $day);
    }

    // Scope untuk filter jadwal yang masih memiliki kursi tersedia
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
