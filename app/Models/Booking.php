<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Booking untuk mengelola data pemesanan tiket travel
 * Menyimpan informasi booking, status pembayaran, dan relasi dengan user dan jadwal
 */
class Booking extends Model
{
    use HasFactory;

    // Atribut yang dapat diisi massal untuk booking
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

    // Relasi dengan model User (satu booking milik satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan model Jadwal (satu booking milik satu jadwal)
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Scope untuk filter booking dengan status pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk filter booking yang disetujui
    public function scopeApproved($query)
    {
        return $query->where('status', 'setuju');
    }

    // Scope untuk filter booking yang dibatalkan
    public function scopeCancelled($query)
    {
        return $query->where('status', 'batal');
    }

    // Scope untuk filter booking yang sudah dibayar
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'sudah_bayar');
    }

    // Scope untuk filter booking yang belum dibayar
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'belum_bayar');
    }

    // Scope untuk filter booking berdasarkan jadwal tertentu
    public function scopeForJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }
}
