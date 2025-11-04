<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'seat_number',
        'status',
        'payment_status',
        'ticket_number',
        'jadwal_tanggal',
        'jadwal_jam',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'setuju');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'batal');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'sudah_bayar');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'belum_bayar');
    }

    public function scopeForJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }
}
