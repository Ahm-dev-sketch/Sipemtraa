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

    public function sendOtp($whatsappNumber, $isRegistration = false)
    {
        if (!$isRegistration) {
            $user = User::where('whatsapp_number', $whatsappNumber)->first();

            if (!$user) {
                return ['success' => false, 'message' => 'Nomor WhatsApp tidak terdaftar'];
            }
        }

        OtpToken::where('whatsapp_number', $whatsappNumber)->delete();

        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpToken::create([
            'whatsapp_number' => $whatsappNumber,
            'otp_code'        => $otpCode,
            'expires_at'      => now()->addMinutes(10),
            'used'            => false
        ]);

        $result = $this->fonnteService->sendOtp($whatsappNumber, $otpCode);

        if ($result['success']) {
            return ['success' => true, 'message' => 'OTP telah dikirim ke WhatsApp Anda'];
        } else {
            Log::warning('Failed to send OTP via Fonnte', ['result' => $result]);
            return ['success' => true, 'message' => 'OTP telah dikirim ke WhatsApp Anda'];
        }
    }

    public function verifyOtp($whatsappNumber, $otpCode)
    {
        $otpToken = OtpToken::valid($whatsappNumber, $otpCode)->first();

        if (!$otpToken) {
            return ['success' => false, 'message' => 'Kode OTP tidak valid atau telah kadaluarsa'];
        }

        $otpToken->update(['used' => true]);

        return ['success' => true, 'message' => 'OTP berhasil diverifikasi'];
    }

    protected function formatNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }

    public function sendAdminNotification(Booking $booking)
    {
        $result = $this->fonnteService->notifyAdminBooking($booking);

        if ($result['success']) {
            Log::info('Admin notification sent successfully', ['booking_id' => $booking->id]);
        } else {
            Log::error('Failed to send admin notification', ['booking_id' => $booking->id, 'result' => $result]);
        }

        return $result['success'];
    }

    public function cleanupExpiredOtps()
    {
        $deleted = OtpToken::where('expires_at', '<', now())->delete();
        Log::info("Expired OTPs cleaned up: {$deleted}");
        return $deleted;
    }
}
