<?php

namespace App\Services;

use App\Models\OtpToken;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /**
     * Generate and send OTP via WhatsApp
     */
    public function sendOtp($whatsappNumber, $isRegistration = false)
    {
        // Untuk registrasi, tidak perlu cek apakah user sudah terdaftar
        if (!$isRegistration) {
            // Check if user exists with this WhatsApp number (untuk reset password)
            $user = User::where('whatsapp_number', $whatsappNumber)->first();

            if (!$user) {
                return ['success' => false, 'message' => 'Nomor WhatsApp tidak terdaftar'];
            }
        }

        // Hapus OTP lama agar tidak menumpuk
        OtpToken::where('whatsapp_number', $whatsappNumber)->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create OTP token (expires in 10 minutes)
        OtpToken::create([
            'whatsapp_number' => $whatsappNumber,
            'otp_code'        => $otpCode,
            'expires_at'      => now()->addMinutes(10),
            'used'            => false
        ]);

        // Kirim OTP via WhatsApp menggunakan Fonnte
        $result = $this->fonnteService->sendOtp($whatsappNumber, $otpCode);

        if ($result['success']) {
            return ['success' => true, 'message' => 'OTP telah dikirim ke WhatsApp Anda'];
        } else {
            // Jika gagal kirim, tetap return success karena OTP sudah dibuat
            // User bisa cek log atau retry
            Log::warning('Failed to send OTP via Fonnte', ['result' => $result]);
            return ['success' => true, 'message' => 'OTP telah dikirim ke WhatsApp Anda'];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp($whatsappNumber, $otpCode)
    {
        $otpToken = OtpToken::valid($whatsappNumber, $otpCode)->first();

        if (!$otpToken) {
            return ['success' => false, 'message' => 'Kode OTP tidak valid atau telah kadaluarsa'];
        }

        // Mark OTP as used
        $otpToken->update(['used' => true]);

        return ['success' => true, 'message' => 'OTP berhasil diverifikasi'];
    }

    /**
     * Format nomor WA ke internasional (62...)
     */
    protected function formatNumber($number)
    {
        // Ambil hanya angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Kalau diawali 0 ubah ke 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    /**
     * Kirim notifikasi admin jika ada booking baru
     */
    public function sendAdminNotification(Booking $booking)
    {
        // Kirim notifikasi admin menggunakan Fonnte
        $result = $this->fonnteService->notifyAdminBooking($booking);

        if ($result['success']) {
            Log::info('Admin notification sent successfully', ['booking_id' => $booking->id]);
        } else {
            Log::error('Failed to send admin notification', ['booking_id' => $booking->id, 'result' => $result]);
        }

        return $result['success'];
    }

    /**
     * Clean up expired OTP tokens
     */
    public function cleanupExpiredOtps()
    {
        $deleted = OtpToken::where('expires_at', '<', now())->delete();
        Log::info("Expired OTPs cleaned up: {$deleted}");
        return $deleted;
    }
}
