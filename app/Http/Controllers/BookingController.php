<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk mengelola sistem pemesanan tiket travel
 * Menangani proses booking wizard, pembatalan, dan manajemen tiket
 */
class BookingController extends Controller
{
    /**
     * Memeriksa apakah user memiliki otorisasi untuk mengakses booking
     */
    private function authorizeBooking(Booking $booking): void
    {
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengakses booking ini.');
        }
    }

    // Generate nomor tiket unik
    private function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'TKT-' . strtoupper(Str::random(10));
        } while (Booking::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }

    // Menampilkan riwayat booking pengguna dengan filter pencarian
    public function index(Request $request)
    {
        $query = Booking::with(['jadwal.rute', 'jadwal.mobil.supir', 'user'])
            ->where('user_id', Auth::id());

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', '%' . $search . '%')
                    ->orWhereHas('jadwal.rute', function ($ruteQuery) use ($search) {
                        $ruteQuery->where('kota_asal', 'like', '%' . $search . '%')
                            ->orWhere('kota_tujuan', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter berdasarkan status booking
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('user.riwayat', compact('bookings'));
    }

    // Menampilkan form pemesanan tiket (legacy method)
    public function create($jadwal_id = null)
    {
        $jadwals = Jadwal::where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
            ->where('is_active', true)
            ->orderBy('tanggal')
            ->get();
        return view('user.pesan', compact('jadwals', 'jadwal_id'));
    }

    // Langkah 1 wizard booking: Pilih kota asal, tujuan, dan tanggal
    public function wizardStep1()
    {
        $kotaAwal = \App\Models\Rute::distinct()->pluck('kota_asal')->sort();
        $kotaTujuan = \App\Models\Rute::distinct()->pluck('kota_tujuan')->sort();

        return view('booking.step1', compact('kotaAwal', 'kotaTujuan'));
    }

    // Memproses data langkah 1 dan menyimpan ke session
    public function processStep1(Request $request)
    {
        $request->validate([
            'kota_awal' => 'required|string',
            'kota_tujuan' => 'required|string|different:kota_awal',
            'tanggal' => 'required|date|after_or_equal:today',
        ]);

        // Simpan data step 1 ke session
        session([
            'booking_step1' => [
                'kota_asal' => $request->kota_awal,
                'kota_tujuan' => $request->kota_tujuan,
                'tanggal' => $request->tanggal,
            ]
        ]);

        return redirect()->route('booking.step2');
    }

    // Langkah 2 wizard booking: Pilih jadwal perjalanan
    public function wizardStep2()
    {
        $step1Data = session('booking_step1');

        if (!$step1Data) {
            return redirect()->route('pesan')->with('error', 'Silakan mulai dari langkah pertama');
        }

        // Cari rute yang sesuai dengan pilihan user
        $routes = \App\Models\Rute::where('kota_asal', $step1Data['kota_asal'])
            ->where('kota_tujuan', $step1Data['kota_tujuan'])
            ->get();

        // Ambil jadwal yang tersedia untuk tanggal tersebut
        $jadwals = Jadwal::whereIn('rute_id', $routes->pluck('id'))
            ->where('tanggal', $step1Data['tanggal'])
            ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
            ->where('is_active', true)
            ->with('rute')
            ->orderBy('jam')
            ->get();

        return view('booking.step2', compact('jadwals', 'step1Data'));
    }

    // Memproses pilihan jadwal dan lanjut ke step 3
    public function processStep2(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Simpan data step 2 ke session
        session([
            'booking_step2' => [
                'jadwal_id' => $jadwal->id,
                'jadwal' => $jadwal,
            ]
        ]);

        return redirect()->route('booking.step3');
    }

    // Booking cepat langsung dari halaman jadwal
    public function quickBooking(Jadwal $jadwal)
    {
        $jadwal->load(['mobil.supir', 'rute']);

        // Buat data step 1 dari jadwal yang dipilih
        $step1Data = [
            'kota_asal' => $jadwal->rute->kota_asal,
            'kota_tujuan' => $jadwal->rute->kota_tujuan,
            'tanggal' => $jadwal->tanggal,
        ];

        $step2Data = [
            'jadwal' => $jadwal,
        ];

        // Simpan ke session dan langsung ke step 3
        session(['booking_step1' => $step1Data]);
        session(['booking_step2' => $step2Data]);

        return redirect()->route('booking.step3');
    }

    // Langkah 3 wizard booking: Pilih kursi
    public function wizardStep3()
    {
        $step1Data = session('booking_step1');
        $step2Data = session('booking_step2');

        if (!$step1Data || !$step2Data) {
            return redirect()->route('pesan')->with('error', 'Silakan mulai dari langkah pertama');
        }

        $jadwal = \App\Models\Jadwal::with('mobil.supir')->find($step2Data['jadwal']->id);

        // Ambil kursi yang sudah dipesan dengan threshold 30 menit
        $threshold = \Carbon\Carbon::now()->subMinutes(config('booking.pending_expiry_minutes', 30));
        $bookedSeats = Booking::where('jadwal_id', $jadwal->id)
            ->where(function ($query) use ($threshold) {
                $query->where('status', 'setuju')
                    ->orWhere(function ($q) use ($threshold) {
                        $q->where('status', 'pending')
                            ->where('created_at', '>=', $threshold);
                    });
            })
            ->pluck('seat_number')
            ->toArray();

        // Daftar kursi yang tersedia (1-14)
        $seats = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14'];

        return view('booking.step3', compact('jadwal', 'seats', 'bookedSeats', 'step1Data', 'step2Data'));
    }

    // Memproses booking akhir dan menyimpan ke database
    public function processStep3(Request $request)
    {
        $step1Data = session('booking_step1');
        $step2Data = session('booking_step2');

        if (!$step1Data || !$step2Data) {
            return redirect()->route('pesan')->with('error', 'Silakan mulai dari langkah pertama');
        }

        $request->validate([
            'seats' => 'required|array|min:1|max:7',
        ]);

        $jadwal = Jadwal::with('mobil')->findOrFail($step2Data['jadwal']->id);

        // Cek batas waktu pemesanan (2 jam sebelum keberangkatan)
        $waktuKeberangkatan = \Carbon\Carbon::parse($jadwal->getRawOriginal('tanggal') . ' ' . $jadwal->jam);
        $batasPesan = $waktuKeberangkatan->copy()->subHours(config('booking.booking_close_hours', 1));
        $waktuSekarang = \Carbon\Carbon::now();

        if ($waktuSekarang->greaterThanOrEqualTo($batasPesan)) {
            $hours = config('booking.booking_close_hours', 1);
            return back()->with('error', "Pemesanan ditutup. Minimal {$hours} jam sebelum keberangkatan.");
        }

        try {
            // Proses booking dalam database transaction untuk konsistensi
            $firstBooking = DB::transaction(function () use ($request, $jadwal) {
                $jadwalLocked = Jadwal::lockForUpdate()->find($jadwal->id);

                $threshold = \Carbon\Carbon::now()->subMinutes(config('booking.pending_expiry_minutes', 30));
                $firstBooking = null;

                // Cek apakah user sudah memiliki booking pending untuk jadwal ini
                $existingPendingBooking = Booking::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('jadwal_id', $jadwal->id)
                    ->where('status', 'pending')
                    ->exists();

                if ($existingPendingBooking) {
                    throw new \Exception('Anda masih memiliki pesanan yang menunggu persetujuan admin untuk jadwal ini. Silahkan batalkan pesanan sebelumnya.');
                }

                // Proses setiap kursi yang dipilih
                foreach ($request->seats as $seat) {
                    // Hitung total booking yang valid
                    $existingBookingsCount = Booking::where('jadwal_id', $jadwal->id)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate()
                        ->count();

                    if ($existingBookingsCount >= $jadwal->mobil->kapasitas) {
                        throw new \Exception('Kapasitas penuh! Tidak ada kursi tersisa.');
                    }

                    // Cek apakah kursi sudah dipesan
                    $existingBooking = Booking::where('jadwal_id', $jadwal->id)
                        ->where('seat_number', $seat)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate()
                        ->first();

                    if ($existingBooking) {
                        throw new \Exception("Kursi $seat sudah dipesan oleh pengguna lain.");
                    }

                    // Buat booking baru
                    $booking = Booking::create([
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'jadwal_id' => $jadwal->id,
                        'seat_number' => $seat,
                        'status' => 'pending',
                        'payment_status' => 'belum_bayar',
                        'ticket_number' => $this->generateTicketNumber(),
                        'jadwal_tanggal' => $jadwal->tanggal,
                        'jadwal_jam' => $jadwal->jam,
                    ]);

                    if (!$firstBooking) {
                        $firstBooking = $booking;
                    }
                }

                return $firstBooking;
            });

            if ($firstBooking) {
                try {
                    app(\App\Services\FonnteService::class)->notifyAdminBooking($firstBooking);
                } catch (\Exception $e) {
                    Log::error('Notification failed but booking succeeded', [
                        'booking_id' => $firstBooking->id,
                        'user_id' => Auth::id(),
                        'error' => $e->getMessage(),
                        'timestamp' => now()
                    ]);
                }
            }

            session()->forget(['booking_step1', 'booking_step2']);

            return redirect()->route('riwayat')->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Booking transaction failed', [
                'user_id' => Auth::id(),
                'jadwal_id' => $request->jadwal_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()
            ]);

            return back()->with('error', 'Booking gagal: ' . $e->getMessage());
        }
    }

    // Menampilkan halaman pemilihan kursi (legacy method)
    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::findOrFail($jadwal_id);

        $threshold = \Carbon\Carbon::now()->subMinutes(config('booking.pending_expiry_minutes', 30));
        $bookedSeats = Booking::where('jadwal_id', $jadwal_id)
            ->where(function ($query) use ($threshold) {
                $query->where('status', 'setuju')
                    ->orWhere(function ($q) use ($threshold) {
                        $q->where('status', 'pending')
                            ->where('created_at', '>=', $threshold);
                    });
            })
            ->pluck('seat_number')
            ->toArray();

        $seats = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14'];

        return view('booking.kursi', compact('jadwal', 'seats', 'bookedSeats'));
    }

    // Proses booking langsung (legacy method)
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats'     => 'required|array|min:1|max:7',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Cek batas waktu pemesanan
        $waktuKeberangkatan = Carbon::parse($jadwal->getRawOriginal('tanggal') . ' ' . $jadwal->jam);
        $batasPesan = $waktuKeberangkatan->copy()->subHour();
        $waktuSekarang = Carbon::now();

        // Log untuk debugging
        Log::info('Booking Time Check:', [
            'waktu_keberangkatan' => $waktuKeberangkatan->format('Y-m-d H:i:s'),
            'batas_pesan' => $batasPesan->format('Y-m-d H:i:s'),
            'waktu_sekarang' => $waktuSekarang->format('Y-m-d H:i:s'),
            'jadwal_id' => $jadwal->id,
            'jadwal_tanggal' => $jadwal->tanggal,
            'jadwal_jam' => $jadwal->jam
        ]);

        if ($waktuSekarang->greaterThanOrEqualTo($batasPesan)) {
            Log::warning('Booking rejected - too close to departure time', [
                'waktu_sekarang' => $waktuSekarang->format('Y-m-d H:i:s'),
                'batas_pesan' => $batasPesan->format('Y-m-d H:i:s'),
                'difference_minutes' => $waktuSekarang->diffInMinutes($batasPesan)
            ]);

            return back()->with('error', 'Pemesanan ditutup. Minimal 1 jam sebelum keberangkatan.');
        }

        try {
            $firstBooking = DB::transaction(function () use ($request, $jadwal) {
                $jadwalLocked = Jadwal::lockForUpdate()->find($jadwal->id);

                $threshold = \Carbon\Carbon::now()->subMinutes(30);
                $firstBooking = null;

                foreach ($request->seats as $seat) {
                    $existingBookingsCount = Booking::where('jadwal_id', $jadwal->id)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate()
                        ->count();

                    if ($existingBookingsCount >= $jadwal->mobil->kapasitas) {
                        throw new \Exception('Kapasitas penuh! Tidak ada kursi tersisa.');
                    }

                    $existingBooking = Booking::where('jadwal_id', $jadwal->id)
                        ->where('seat_number', $seat)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate()
                        ->first();

                    if ($existingBooking) {
                        throw new \Exception("Kursi $seat sudah dipesan oleh pengguna lain.");
                    }

                    $booking = Booking::create([
                        'user_id'       => Auth::id(),
                        'jadwal_id'     => $jadwal->id,
                        'seat_number'   => $seat,
                        'status'        => 'pending',
                        'payment_status' => 'belum_bayar',
                        'ticket_number' => $this->generateTicketNumber(),
                        'jadwal_tanggal' => $jadwal->tanggal,
                        'jadwal_jam'    => $jadwal->jam,
                    ]);

                    if (!$firstBooking) {
                        $firstBooking = $booking;
                    }
                }

                return $firstBooking;
            });

            return redirect()->route('riwayat')->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Booking transaction failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    // Update status booking oleh admin
    public function updateStatus(Request $request, Booking $booking)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengubah status pesanan.');
        }

        $request->validate([
            'status' => 'required|in:pending,setuju,batal'
        ]);

        // Cek jika booking sudah dibayar, tidak boleh diubah statusnya
        if ($booking->payment_status === 'sudah_bayar' && $request->status !== $booking->status) {
            return back()->with('error', 'Tidak dapat mengubah status pesanan yang sudah dibayar. Kelola di menu Pembayaran.');
        }

        $oldStatus = $booking->status;

        $booking->update([
            'status' => $request->status
        ]);

        // Log perubahan status
        Log::info('Booking status updated', [
            'booking_id' => $booking->id,
            'admin_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'timestamp' => now()
        ]);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    // API endpoint untuk mendapatkan kursi yang sudah dipesan (untuk AJAX)
    public function getSeats($id)
    {
        $threshold = \Carbon\Carbon::now()->subMinutes(config('booking.pending_expiry_minutes', 30));
        $bookedSeats = Booking::where('jadwal_id', $id)
            ->where(function ($query) use ($threshold) {
                $query->where('status', 'setuju')
                    ->orWhere(function ($q) use ($threshold) {
                        $q->where('status', 'pending')
                            ->where('created_at', '>=', $threshold);
                    });
            })
            ->pluck('seat_number')
            ->toArray();

        return response()->json($bookedSeats);
    }

    // Download tiket dalam format PDF
    public function downloadTicket(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== 'setuju' || $booking->payment_status !== 'sudah_bayar') {
            abort(403, 'Tiket belum dapat didownload. Status harus disetujui dan sudah dibayar.');
        }

        $pdf = Pdf::loadView('booking.ticket', compact('booking'));

        return $pdf->download('e-ticket-' . $booking->ticket_number . '.pdf');
    }

    // Menampilkan tiket dalam format HTML (untuk preview)
    public function viewTicket($ticketNumber)
    {
        $booking = Booking::where('ticket_number', $ticketNumber)->with('jadwal.rute', 'user')->firstOrFail();

        $this->authorizeBooking($booking);

        if ($booking->status !== 'setuju' || $booking->payment_status !== 'sudah_bayar') {
            abort(403, 'Tiket belum dapat dilihat. Status harus disetujui dan sudah dibayar.');
        }

        return view('booking.ticket', compact('booking'));
    }


    // Membatalkan booking oleh user
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $this->authorizeBooking($booking);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Cek batas waktu pembatalan (2 jam sebelum keberangkatan)
        $waktuKeberangkatan = \Carbon\Carbon::parse($booking->jadwal_tanggal . ' ' . $booking->jadwal_jam);
        $batasCancel = $waktuKeberangkatan->copy()->subHours(config('booking.cancel_close_hours', 2));
        $waktuSekarang = \Carbon\Carbon::now();

        if ($waktuSekarang->greaterThanOrEqualTo($batasCancel)) {
            $hours = config('booking.cancel_close_hours', 2);
            return back()->with('error', "Tidak dapat membatalkan pesanan. Minimal {$hours} jam sebelum keberangkatan.");
        }

        $booking->update(['status' => 'batal']);

        // Kirim notifikasi ke admin
        try {
            $fonnteService = app(\App\Services\FonnteService::class);
            $fonnteService->notifyAdminCancellation($booking);
        } catch (\Exception $e) {
            Log::error('Gagal kirim notifikasi pembatalan ke admin', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
