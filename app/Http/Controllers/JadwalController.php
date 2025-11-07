<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controller untuk mengelola jadwal perjalanan travel
 * Menangani tampilan jadwal dan informasi kursi yang sudah dipesan
 */
class JadwalController extends Controller
{
    // Mengambil data kursi yang sudah dipesan untuk jadwal tertentu
    public function getBookedSeats($jadwal_id)
    {
        // Threshold 30 menit untuk booking pending yang masih valid
        $threshold = Carbon::now()->subMinutes(30);

        $bookedSeats = Booking::where('jadwal_id', $jadwal_id)
            ->where(function ($query) use ($threshold) {
                $query->where('status', 'setuju')
                    ->orWhere(function ($q) use ($threshold) {
                        $q->where('status', 'pending')
                            ->where('created_at', '>=', $threshold);
                    });
            })
            ->pluck('seat_number');

        return response()->json($bookedSeats);
    }
    // Menampilkan daftar jadwal perjalanan dengan fitur pencarian
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query jadwal aktif dengan relasi rute, filter tanggal hari ini ke depan
        $jadwals = Jadwal::with('rute')
            ->where('is_active', true)
            ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
            ->when($search, function ($query, $search) {
                $query->whereHas('rute', function ($q) use ($search) {
                    $q->where('kota_asal', 'like', "%{$search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$search}%");
                })
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhere('jam', 'like', "%{$search}%");
            })
            ->orderBy('tanggal')
            ->paginate(10);

        return view('user.jadwal', compact('jadwals', 'search'));
    }
}
