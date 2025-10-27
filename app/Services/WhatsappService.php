<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected $token;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->token = env('WHATSAPP_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function sendMessage($to, $message)
    {
        $url = "https://graph.facebook.com/v17.0/{$this->phoneNumberId}/messages";

        $response = Http::withToken($this->token)->post($url, [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "text",
            "text" => [
                "body" => $message
            ]
        ]);

        return $response->json();
    }

    public function notifyAdminBooking($booking)
    {
        $adminNumber = env('WHATSAPP_ADMIN_NUMBER');
        $message = "📢 Ada pemesanan baru!\n"
            . "Nama: " . $booking->user->name . "\n"
            . "Jadwal: " . $booking->jadwal->tanggal . " " . $booking->jadwal->jam . "\n"
            . "Kursi: " . $booking->seat_number;

        return $this->sendMessage($adminNumber, $message);
    }

    public function notifyBookingStatusUpdate($booking)
    {
        $userNumber = $booking->user->whatsapp_number;
        $status = ucfirst($booking->status);

        $message = "📋 Status Booking Anda Telah Diperbarui\n\n"
            . "Status: {$status}\n"
            . "Jadwal: " . $booking->jadwal->tanggal . " " . $booking->jadwal->jam . "\n"
            . "Rute: " . $booking->jadwal->rute->kota_asal . " → " . $booking->jadwal->rute->kota_tujuan . "\n"
            . "Kursi: " . $booking->seat_number . "\n"
            . "Mobil: " . $booking->jadwal->mobil->merk . " (" . $booking->jadwal->mobil->nomor_polisi . ")";

        if ($booking->status === 'setuju') {
            $message .= "\n\n✅ Booking Anda telah dikonfirmasi. Selamat berpergian!";
        } elseif ($booking->status === 'batal') {
            $message .= "\n\n❌ Booking Anda telah dibatalkan.";
        }

        return $this->sendMessage($userNumber, $message);
    }
}
