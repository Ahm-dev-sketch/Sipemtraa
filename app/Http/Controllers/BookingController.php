<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\WhatsappService;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    private function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'TKT-' . strtoupper(Str::random(10));
        } while (Booking::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['jadwal.rute', 'jadwal.mobil.supir', 'user'])
            ->where('user_id', Auth::id());

        // Search by ticket number or route
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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }



        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('user.riwayat', compact('bookings'));
    }

    // Legacy method - kept for backward compatibility
    public function create($jadwal_id = null)
    {
        $jadwals = Jadwal::orderBy('tanggal')->get();
        return view('user.pesan', compact('jadwals', 'jadwal_id'));
    }

    // ===== MULTI-STEP BOOKING WIZARD =====

    // Step 1: Pilih Perjalanan Anda
    public function wizardStep1()
    {
        // Get unique cities from rutes table for dropdowns
        $kotaAwal = \App\Models\Rute::distinct()->pluck('kota_asal')->sort();
        $kotaTujuan = \App\Models\Rute::distinct()->pluck('kota_tujuan')->sort();

        return view('booking.step1', compact('kotaAwal', 'kotaTujuan'));
    }

    // Process Step 1 and redirect to Step 2
    public function processStep1(Request $request)
    {
        $request->validate([
            'kota_awal' => 'required|string',
            'kota_tujuan' => 'required|string|different:kota_awal',
            'tanggal' => 'required|date|after_or_equal:today',
        ]);

        // Store step 1 data in session
        session([
            'booking_step1' => [
                'kota_asal' => $request->kota_awal,
                'kota_tujuan' => $request->kota_tujuan,
                'tanggal' => $request->tanggal,
            ]
        ]);

        return redirect()->route('booking.step2');
    }

    // Step 2: Pilih Rute
    public function wizardStep2()
    {
        $step1Data = session('booking_step1');

        if (!$step1Data) {
            return redirect()->route('pesan')->with('error', 'Silakan mulai dari langkah pertama');
        }

        // Find available routes based on step 1 data
        $routes = \App\Models\Rute::where('kota_asal', $step1Data['kota_asal'])
            ->where('kota_tujuan', $step1Data['kota_tujuan'])
            ->get();

        // Find available schedules for these routes on the selected date
        $jadwals = Jadwal::whereIn('rute_id', $routes->pluck('id'))
            ->where('tanggal', $step1Data['tanggal'])
            ->with('rute')
            ->orderBy('jam')
            ->get();

        return view('booking.step2', compact('jadwals', 'step1Data'));
    }

    // Process Step 2 and redirect to Step 3
    public function processStep2(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Store step 2 data in session
        session([
            'booking_step2' => [
                'jadwal_id' => $jadwal->id,
                'jadwal' => $jadwal,
            ]
        ]);

        return redirect()->route('booking.step3');
    }

    // Step 3: Pilih Kursi
    public function wizardStep3()
    {
        $step1Data = session('booking_step1');
        $step2Data = session('booking_step2');

        if (!$step1Data || !$step2Data) {
            return redirect()->route('pesan')->with('error', 'Silakan mulai dari langkah pertama');
        }

        $jadwal = \App\Models\Jadwal::with('mobil.supir')->find($step2Data['jadwal']->id);

        // Auto-cleanup: Expired pending bookings menjadi 'expired' untuk free up seats
        $threshold = \Carbon\Carbon::now()->subMinutes(30);
        Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->update(['status' => 'expired']);

        // Kursi yang sudah dibooking dengan status setuju (approved) atau pending yang belum expired (30 menit)
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

        // Dynamic seat layout based on mobil capacity
        $capacity = $jadwal->mobil->kapasitas ?? 13;
        $seats = [];
        for ($i = 1; $i <= $capacity; $i++) {
            $seats[] = 'A' . $i;
        }

        return view('booking.step3', compact('jadwal', 'seats', 'bookedSeats', 'step1Data', 'step2Data'));
    }

    // Process Step 3 and save booking
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

        // Cek batas waktu pemesanan (H-1 jam)
        $waktuKeberangkatan = \Carbon\Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam);
        $batasPesan = $waktuKeberangkatan->copy()->subHour();
        $waktuSekarang = \Carbon\Carbon::now();

        if ($waktuSekarang->greaterThanOrEqualTo($batasPesan)) {
            return back()->with('error', 'Pemesanan ditutup. Minimal 1 jam sebelum keberangkatan.');
        }

        // Gunakan Database Transaction untuk mencegah race condition
        try {
            $firstBooking = DB::transaction(function () use ($request, $jadwal) {
                // Lock jadwal untuk mencegah concurrent booking
                $jadwalLocked = Jadwal::lockForUpdate()->find($jadwal->id);

                $threshold = \Carbon\Carbon::now()->subMinutes(30);
                $firstBooking = null;

                // Validasi total kapasitas
                $existingBookingsCount = Booking::where('jadwal_id', $jadwal->id)
                    ->where(function ($query) use ($threshold) {
                        $query->where('status', 'setuju')
                            ->orWhere(function ($q) use ($threshold) {
                                $q->where('status', 'pending')
                                    ->where('created_at', '>=', $threshold);
                            });
                    })
                    ->count();

                if (($existingBookingsCount + count($request->seats)) > $jadwal->mobil->kapasitas) {
                    throw new \Exception('Kapasitas penuh! Hanya tersisa ' . ($jadwal->mobil->kapasitas - $existingBookingsCount) . ' kursi.');
                }

                foreach ($request->seats as $seat) {
                    // Cek apakah kursi sudah diambil (approved ATAU pending yang masih valid)
                    $existingBooking = Booking::where('jadwal_id', $jadwal->id)
                        ->where('seat_number', $seat)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate() // Pessimistic locking
                        ->first();

                    if ($existingBooking) {
                        throw new \Exception("Kursi $seat sudah dipesan oleh pengguna lain.");
                    }

                    // Simpan tiap kursi sebagai booking dengan status pending
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

            // Kirim notif admin pakai booking pertama (di luar transaction)
            if ($firstBooking) {
                try {
                    app(\App\Services\FonnteService::class)->notifyAdminBooking($firstBooking);
                } catch (\Exception $e) {
                    Log::error('Failed to send Fonnte notification: ' . $e->getMessage());
                    // Continue anyway, booking is successful
                }
            }

            // Clear session data
            session()->forget(['booking_step1', 'booking_step2']);

            return redirect()->route('riwayat')->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Booking failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::findOrFail($jadwal_id);

        // Kursi yang sudah dibooking dengan status setuju (approved)
        $bookedSeats = Booking::where('jadwal_id', $jadwal_id)
            ->where('status', 'setuju')
            ->pluck('seat_number')
            ->toArray();

        // Layout kursi minibus (13 kursi penumpang)
        $seats = ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A11', 'A12', 'A13'];

        return view('booking.kursi', compact('jadwal', 'seats', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'seats'     => 'required|array|min:1|max:7',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Cek batas waktu pemesanan (H-1 jam)
        $waktuKeberangkatan = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->jam);
        $batasPesan = $waktuKeberangkatan->copy()->subHour();
        $waktuSekarang = Carbon::now();

        // Debug logging
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

        // Gunakan Database Transaction untuk mencegah race condition
        try {
            $firstBooking = DB::transaction(function () use ($request, $jadwal) {
                // Lock jadwal untuk mencegah concurrent booking
                $jadwalLocked = Jadwal::lockForUpdate()->find($jadwal->id);

                $threshold = \Carbon\Carbon::now()->subMinutes(30);
                $firstBooking = null;

                // Validasi total kapasitas
                $existingBookingsCount = Booking::where('jadwal_id', $jadwal->id)
                    ->where(function ($query) use ($threshold) {
                        $query->where('status', 'setuju')
                            ->orWhere(function ($q) use ($threshold) {
                                $q->where('status', 'pending')
                                    ->where('created_at', '>=', $threshold);
                            });
                    })
                    ->count();

                if (($existingBookingsCount + count($request->seats)) > $jadwal->mobil->kapasitas) {
                    throw new \Exception('Kapasitas penuh! Hanya tersisa ' . ($jadwal->mobil->kapasitas - $existingBookingsCount) . ' kursi.');
                }

                foreach ($request->seats as $seat) {
                    // Cek apakah kursi sudah diambil (approved ATAU pending yang masih valid)
                    $existingBooking = Booking::where('jadwal_id', $jadwal->id)
                        ->where('seat_number', $seat)
                        ->where(function ($query) use ($threshold) {
                            $query->where('status', 'setuju')
                                ->orWhere(function ($q) use ($threshold) {
                                    $q->where('status', 'pending')
                                        ->where('created_at', '>=', $threshold);
                                });
                        })
                        ->lockForUpdate() // Pessimistic locking
                        ->first();

                    if ($existingBooking) {
                        throw new \Exception("Kursi $seat sudah dipesan oleh pengguna lain.");
                    }

                    // Simpan tiap kursi sebagai booking dengan status pending
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

            // Kirim notif admin pakai booking pertama
            if ($firstBooking) {
                app(WhatsappService::class)->notifyAdminBooking($firstBooking);
            }

            return redirect()->route('riwayat')->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Booking transaction failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,setuju,batal'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    public function getSeats($id)
    {
        $threshold = \Carbon\Carbon::now()->subMinutes(30);
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

    public function downloadTicket(Booking $booking)
    {
        // Pastikan user hanya bisa download tiketnya sendiri atau admin
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Pastikan booking sudah disetujui dan sudah bayar
        if ($booking->status !== 'setuju' || $booking->payment_status !== 'sudah_bayar') {
            abort(403, 'Tiket belum dapat didownload');
        }

        $pdf = Pdf::loadView('booking.ticket', compact('booking'));

        return $pdf->download('e-ticket-' . $booking->ticket_number . '.pdf');
    }

    public function viewTicket($ticketNumber)
    {
        $booking = Booking::where('ticket_number', $ticketNumber)->firstOrFail();

        // Pastikan user hanya bisa lihat tiketnya sendiri atau admin
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Pastikan booking sudah disetujui dan sudah bayar
        if ($booking->status !== 'setuju' || $booking->payment_status !== 'sudah_bayar') {
            abort(403, 'Tiket belum dapat dilihat');
        }

        return view('booking.ticket', compact('booking'));
    }

    // Batal pesanan
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Pastikan hanya pemilik booking yang bisa membatalkan
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk membatalkan pesanan ini.');
        }

        // Hanya bisa dibatalkan jika masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Update status ke batal
        $booking->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
