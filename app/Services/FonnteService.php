<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FonnteService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp melalui Fonnte
     *
     * @param string $target Nomor WhatsApp tujuan (format: 628xxx)
     * @param string $message Isi pesan
     * @return array Response dari API
     */
    public function sendMessage($target, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl . '/send', [
                'target' => $this->formatNumber($target),
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ]);

            $result = $response->json();

            // Log untuk debugging
            Log::info('Fonnte Send Message', [
                'target' => $target,
                'status' => $response->status(),
                'response' => $result
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte Send Message Error', [
                'target' => $target,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Kirim OTP ke nomor WhatsApp
     *
     * @param string $target Nomor WhatsApp
     * @param string $otpCode Kode OTP
     * @return array
     */
    public function sendOtp($target, $otpCode)
    {
        $message = "🔐 *Kode OTP Anda*\n\n"
            . "Kode: *{$otpCode}*\n"
            . "Berlaku selama 10 menit.\n\n"
            . "⚠️ Jangan bagikan kode ini kepada siapapun!";

        return $this->sendMessage($target, $message);
    }

    /**
     * Kirim notifikasi booking baru ke admin
     *
     * @param \App\Models\Booking $booking
     * @return array
     */
    public function notifyAdminBooking($booking)
    {
        $adminNumber = config('services.fonnte.admin_number');

        $user = $booking->user;
        $jadwal = $booking->jadwal;
        $rute = $jadwal->rute;
        $mobil = $jadwal->mobil;

        // Ambil tanggal dan hari dari booking jika tersedia, fallback ke jadwal
        $rawTanggal = $booking->tanggal ?? $jadwal->tanggal ?? null;
        $hari = $booking->jadwal_hari_keberangkatan ?? $jadwal->hari_keberangkatan ?? null;
        try {
            $tanggalFormatted = $rawTanggal ? Carbon::parse($rawTanggal)->format('d/m/Y') : '-';
        } catch (\Exception $e) {
            $tanggalFormatted = $rawTanggal ?? '-';
        }

        if (!$hari && $rawTanggal) {
            try {
                $dayIndex = Carbon::parse($rawTanggal)->dayOfWeek; // 0-6
                $dayMap = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu'];
                $hari = $dayMap[$dayIndex] ?? null;
            } catch (\Exception $e) {
                $hari = null;
            }
        }

        $hariText = $hari ? $hari : '-';

        $message = "📢 *PEMESANAN BARU!*\n\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "👤 *Pelanggan*\n"
            . "Nama: {$user->name}\n"
            . "WA: {$user->whatsapp_number}\n\n"
            . "🚗 *Detail Perjalanan*\n"
            . "Rute: {$rute->kota_asal} → {$rute->kota_tujuan}\n"
            . "Hari: {$hariText}, Tanggal: {$tanggalFormatted}\n"
            . "Jam: {$jadwal->jam}\n"
            . "Kursi: {$booking->seat_number}\n"
            . "Mobil: {$mobil->merk} ({$mobil->nomor_polisi})\n"
            . "━━━━━━━━━━━━━━━━━━\n\n"
            . "💰 Harga: Rp " . number_format($jadwal->harga, 0, ',', '.') . "\n"
            . "📋 Status: *PENDING*\n\n"
            . "Silakan konfirmasi pesanan ini.";

        return $this->sendMessage($adminNumber, $message);
    }

    /**
     * Kirim notifikasi update status booking ke user
     *
     * @param \App\Models\Booking $booking
     * @return array
     */
    public function notifyBookingStatusUpdate($booking)
    {
        $userNumber = $booking->user->whatsapp_number;
        $jadwal = $booking->jadwal;
        $rute = $jadwal->rute;
        $mobil = $jadwal->mobil;

        $statusEmoji = [
            'pending' => '⏳',
            'setuju' => '✅',
            'batal' => '❌'
        ];

        $emoji = $statusEmoji[$booking->status] ?? '📋';
        $statusText = strtoupper($booking->status);

        $message = "{$emoji} *STATUS BOOKING DIPERBARUI*\n\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "Status: *{$statusText}*\n\n"
            . "📋 *Detail Booking*\n"
            . "Rute: {$rute->kota_asal} → {$rute->kota_tujuan}\n"
            . "Tanggal: {$jadwal->tanggal}\n"
            . "Jam: {$jadwal->jam}\n"
            . "Kursi: {$booking->seat_number}\n"
            . "Mobil: {$mobil->merk} ({$mobil->nomor_polisi})\n"
            . "━━━━━━━━━━━━━━━━━━\n\n";

        if ($booking->status === 'setuju') {
            $message .= "✅ *Booking Anda telah dikonfirmasi!*\n"
                . "Pastikan anda sudah siap ketika sudah dijemput.\n"
                . "Selamat menimati perjalanan! 🚗";
        } elseif ($booking->status === 'batal') {
            $message .= "❌ *Booking Anda telah dibatalkan.*\n"
                . "Silakan hubungi admin untuk informasi lebih lanjut.";
        }

        return $this->sendMessage($userNumber, $message);
    }

    /**
     * Kirim notifikasi pembatalan booking ke admin
     *
     * @param \App\Models\Booking $booking
     * @return array
     */
    public function notifyAdminCancellation($booking)
    {
        $adminNumber = config('services.fonnte.admin_number');

        $user = $booking->user;
        $jadwal = $booking->jadwal;
        $rute = $jadwal->rute;
        $mobil = $jadwal->mobil;

        // Ambil tanggal dan hari dari booking jika tersedia, fallback ke jadwal
        $rawTanggal = $booking->tanggal ?? $jadwal->tanggal ?? null;
        $hari = $booking->jadwal_hari_keberangkatan ?? $jadwal->hari_keberangkatan ?? null;
        try {
            $tanggalFormatted = $rawTanggal ? Carbon::parse($rawTanggal)->format('d/m/Y') : '-';
        } catch (\Exception $e) {
            $tanggalFormatted = $rawTanggal ?? '-';
        }
        if (!$hari && $rawTanggal) {
            try {
                $dayIndex = Carbon::parse($rawTanggal)->dayOfWeek;
                $dayMap = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu'];
                $hari = $dayMap[$dayIndex] ?? null;
            } catch (\Exception $e) {
                $hari = null;
            }
        }
        $hariText = $hari ? $hari : '-';

        $message = "🚫 *PEMBATALAN BOOKING*\n\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "👤 *Pelanggan*\n"
            . "Nama: {$user->name}\n"
            . "WA: {$user->whatsapp_number}\n\n"
            . "🚗 *Detail Perjalanan*\n"
            . "Rute: {$rute->kota_asal} → {$rute->kota_tujuan}\n"
            . "Hari: {$hariText}, Tanggal: {$tanggalFormatted}\n"
            . "Jam: {$jadwal->jam}\n"
            . "Kursi: {$booking->seat_number}\n"
            . "Mobil: {$mobil->merk} ({$mobil->nomor_polisi})\n"
            . "━━━━━━━━━━━━━━━━━━\n\n"
            . "💰 Harga: Rp " . number_format($jadwal->harga, 0, ',', '.') . "\n"
            . "📋 Status: *DIBATALKAN oleh User*\n"
            . "🕐 Waktu: " . now()->format('d/m/Y H:i') . "\n\n"
            . "⚠️ Kursi sekarang tersedia kembali.";

        return $this->sendMessage($adminNumber, $message);
    }

    /**
     * Kirim notifikasi ke admin tentang booking yang di-cancel otomatis
     * karena expired (lebih dari 30 menit tidak ada aksi admin)
     *
     * @param \App\Models\Booking $booking
     * @return array Response dari Fonnte API
     */
    public function notifyAdminAutoCancellation($booking)
    {
        $adminNumber = config('services.fonnte.admin_number');

        $user = $booking->user;
        $jadwal = $booking->jadwal;
        $rute = $jadwal->rute;
        $mobil = $jadwal->mobil;

        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $createdAt = $booking->created_at->format('d/m/Y H:i');

        // Ambil tanggal dan hari dari booking jika tersedia, fallback ke jadwal
        $rawTanggal = $booking->tanggal ?? $jadwal->tanggal ?? null;
        $hari = $booking->jadwal_hari_keberangkatan ?? $jadwal->hari_keberangkatan ?? null;
        try {
            $tanggalFormatted = $rawTanggal ? Carbon::parse($rawTanggal)->format('d/m/Y') : '-';
        } catch (\Exception $e) {
            $tanggalFormatted = $rawTanggal ?? '-';
        }
        if (!$hari && $rawTanggal) {
            try {
                $dayIndex = Carbon::parse($rawTanggal)->dayOfWeek;
                $dayMap = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 0 => 'Minggu'];
                $hari = $dayMap[$dayIndex] ?? null;
            } catch (\Exception $e) {
                $hari = null;
            }
        }
        $hariText = $hari ? $hari : '-';

        $message = "⏰ *AUTO-CANCEL: BOOKING EXPIRED*\n\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "⚠️ Booking otomatis dibatalkan karena\n"
            . "tidak ada tindakan admin dalam {$expiryMinutes} menit.\n\n"
            . "👤 *Pelanggan*\n"
            . "Nama: {$user->name}\n"
            . "WA: {$user->whatsapp_number}\n\n"
            . "🚗 *Detail Perjalanan*\n"
            . "Rute: {$rute->kota_asal} → {$rute->kota_tujuan}\n"
            . "Hari: {$hariText}, Tanggal: {$tanggalFormatted}\n"
            . "Jam: {$jadwal->jam}\n"
            . "Kursi: {$booking->seat_number}\n"
            . "Mobil: {$mobil->merk} ({$mobil->nomor_polisi})\n"
            . "━━━━━━━━━━━━━━━━━━\n\n"
            . "💰 Harga: Rp " . number_format($jadwal->harga, 0, ',', '.') . "\n"
            . "📋 Ticket: {$booking->ticket_number}\n"
            . "📅 Dibuat: {$createdAt}\n"
            . "🕐 Auto-Cancel: " . now()->format('d/m/Y H:i') . "\n\n"
            . "⚠️ Kursi kembali tersedia.\n"
            . "💡 *Tips*: Proses booking lebih cepat untuk menghindari auto-cancel.";

        return $this->sendMessage($adminNumber, $message);
    }

    /**
     * Format nomor WhatsApp ke format internasional
     *
     * @param string $number
     * @return string
     */
    protected function formatNumber($number)
    {
        // Hapus semua karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali dengan 0, ganti dengan 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        // Jika tidak diawali dengan 62, tambahkan 62
        if (substr($number, 0, 2) !== '62') {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Cek sisa kuota pesan Fonnte
     *
     * @return array
     */
    public function checkQuota()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl . '/validate');

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte Check Quota Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
