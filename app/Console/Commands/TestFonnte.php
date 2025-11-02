<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FonnteService;

class TestFonnte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fonnte:test {--number= : Nomor WhatsApp tujuan test (opsional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test integrasi Fonnte WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=================================');
        $this->info('   FONNTE INTEGRATION TEST');
        $this->info('=================================');
        $this->newLine();

        // Test 1: Cek Konfigurasi
        $this->info('📋 Test 1: Cek Konfigurasi');
        $this->line('-----------------------------------');

        $token = config('services.fonnte.token');
        $adminNumber = config('services.fonnte.admin_number');

        if (!$token || $token === 'your_fonnte_token_here') {
            $this->error('❌ FONNTE_TOKEN belum diisi di file .env');
            $this->warn('   Silakan isi FONNTE_TOKEN di file .env');
            return 1;
        }

        if (!$adminNumber) {
            $this->error('❌ FONNTE_ADMIN_NUMBER belum diisi di file .env');
            $this->warn('   Silakan isi FONNTE_ADMIN_NUMBER di file .env');
            return 1;
        }

        $this->line('✅ Token: ' . substr($token, 0, 10) . '...');
        $this->line('✅ Admin Number: ' . $adminNumber);
        $this->newLine();

        // Test 2: Cek Kuota
        $this->info('📊 Test 2: Cek Kuota Fonnte');
        $this->line('-----------------------------------');

        $fonnte = app(FonnteService::class);
        $quota = $fonnte->checkQuota();

        if ($quota['success']) {
            $this->line('✅ Koneksi Fonnte berhasil!');
            $this->line('📱 Device Status: ' . ($quota['data']['device_status'] ?? 'unknown'));

            if (isset($quota['data']['quota'])) {
                $this->line('💬 Quota: ' . $quota['data']['quota']);
            }
        } else {
            $this->error('❌ Gagal terhubung ke Fonnte');
            $this->warn('   Error: ' . ($quota['error'] ?? 'Unknown error'));
            return 1;
        }
        $this->newLine();

        // Test 3: Kirim Pesan Test
        $targetNumber = $this->option('number') ?: $adminNumber;

        $this->info('📤 Test 3: Kirim Pesan Test');
        $this->line('-----------------------------------');
        $this->line('Mengirim pesan test ke: ' . $targetNumber);

        if ($this->confirm('Lanjutkan kirim pesan test?', true)) {
            $testMessage = "🧪 *Test Pesan*\n\nIni adalah pesan test dari sistem SIPEMTRAA.\n\nWaktu: " . now()->format('Y-m-d H:i:s');

            $result = $fonnte->sendMessage($targetNumber, $testMessage);

            if ($result['success']) {
                $this->line('✅ Pesan test berhasil dikirim!');
                $this->line('   Silakan cek WhatsApp: ' . $targetNumber);
            } else {
                $this->error('❌ Gagal mengirim pesan test');
                $this->warn('   Error: ' . ($result['error'] ?? json_encode($result['data'] ?? [])));
            }
        } else {
            $this->warn('⏭️  Skip kirim pesan test');
        }
        $this->newLine();

        // Test 4: Test OTP
        $this->info('🔐 Test 4: Test Kirim OTP');
        $this->line('-----------------------------------');

        if ($this->confirm('Kirim OTP test ke ' . $targetNumber . '?', false)) {
            $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $result = $fonnte->sendOtp($targetNumber, $otpCode);

            if ($result['success']) {
                $this->line('✅ OTP berhasil dikirim!');
                $this->line('   Kode OTP: ' . $otpCode);
                $this->line('   Silakan cek WhatsApp: ' . $targetNumber);
            } else {
                $this->error('❌ Gagal mengirim OTP');
                $this->warn('   Error: ' . ($result['error'] ?? 'Unknown error'));
            }
        } else {
            $this->warn('⏭️  Skip kirim OTP');
        }
        $this->newLine();

        // Summary
        $this->info('=================================');
        $this->info('   TEST SELESAI');
        $this->info('=================================');
        $this->newLine();

        $this->line('📝 Kesimpulan:');
        $this->line('- Jika semua test ✅, maka integrasi Fonnte sudah siap');
        $this->line('- Jika ada test ❌, cek error message dan perbaiki');
        $this->line('- Lihat log di storage/logs/laravel.log untuk detail');
        $this->newLine();

        $this->line('🎯 Next Steps:');
        $this->line('1. Coba lakukan booking dari aplikasi');
        $this->line('2. Cek apakah admin menerima notifikasi');
        $this->line('3. Coba update status booking dari admin panel');
        $this->line('4. Cek apakah user menerima notifikasi update status');
        $this->newLine();

        return 0;
    }
}
