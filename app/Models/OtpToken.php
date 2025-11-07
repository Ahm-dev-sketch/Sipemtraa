<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model OtpToken untuk mengelola token OTP untuk verifikasi WhatsApp
 * Menyimpan kode OTP, nomor WhatsApp, waktu kadaluarsa, dan status penggunaan
 */
class OtpToken extends Model
{
    use HasFactory;

    // Atribut yang dapat diisi massal untuk OTP token
    protected $fillable = [
        'whatsapp_number',
        'otp_code',
        'expires_at',
        'used'
    ];

    // Casting atribut untuk tipe data tertentu
    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    // Scope untuk mendapatkan token OTP yang masih valid
    public function scopeValid($query, $whatsappNumber, $otpCode)
    {
        return $query->where('whatsapp_number', $whatsappNumber)
            ->where('otp_code', $otpCode)
            ->where('used', false)
            ->where('expires_at', '>', now());
    }
}
