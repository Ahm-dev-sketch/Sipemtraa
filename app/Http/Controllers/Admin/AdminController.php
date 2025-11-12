<?php

namespace App\Http\Controllers\Admin;

// Import model dan library yang diperlukan
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola panel admin sistem pemesanan tiket travel
 * Menangani dashboard, manajemen jadwal, booking, pelanggan, rute, mobil, dan supir
 */
class AdminController extends Controller
{
    // Menampilkan dashboard admin dengan statistik utama
    public function dashboard()
    {
        // Hitung total pendapatan bulan ini dari booking yang sudah disetujui dan dibayar
        $totalPendapatanBulanIni = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung jumlah pemesanan bulan ini
        $jumlahPemesananBulanIni = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Hitung jumlah perjalanan aktif (jadwal yang masih memiliki tanggal mendatang)
        // Jadwal model tidak menyimpan kolom `tanggal`; hitung berdasarkan helper getUpcomingDates()
        $perjalananAktif = Jadwal::where('is_active', true)
            ->get()
            ->filter(function ($jadwal) {
                return $jadwal->getUpcomingDates(1)->count() > 0;
            })->count();

        // Hitung total pelanggan (user dengan role 'user')
        $totalPelanggan = User::where('role', 'user')->count();

        // Hitung pendapatan 7 hari terakhir untuk chart
        $pendapatan7Hari = [];
        $labels7Hari = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Hari[] = $date->format('d M');

            $pendapatanHari = Booking::where('status', 'setuju')
                ->where('payment_status', 'sudah_bayar')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->with('jadwal')
                ->get()
                ->sum(function ($booking) {
                    return $booking->jadwal->harga;
                });

            $pendapatan7Hari[] = $pendapatanHari;
        }

        return view('admin.dashboard', [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalJadwal' => Jadwal::count(),
            'totalBooking' => Booking::count(),
            'totalPendapatanBulanIni' => $totalPendapatanBulanIni,
            'jumlahPemesananBulanIni' => $jumlahPemesananBulanIni,
            'perjalananAktif' => $perjalananAktif,
            'totalPelanggan' => $totalPelanggan,
            'pendapatan7Hari' => $pendapatan7Hari,
            'labels7Hari' => $labels7Hari,
        ]);
    }

    // Menampilkan daftar jadwal dengan fitur pencarian
    public function jadwals(Request $request)
    {
        $search = $request->input('search');

        $jadwals = Jadwal::with(['rute', 'mobil'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('rute', function ($q) use ($search) {
                    $q->where('kota_asal', 'like', "%{$search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$search}%");
                })
                    // jadwal.tanggal tidak ada di tabel jadwals; skip searching that column
                    ->orWhere('jam', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%");
            })->latest()->paginate(10);

        return view('admin.jadwals', compact('jadwals', 'search'));
    }

    // Menampilkan form tambah jadwal baru
    public function createJadwal()
    {
        // Ambil rute yang unik berdasarkan kombinasi kota asal-tujuan
        $rutes = \App\Models\Rute::all()
            ->groupBy(function ($item) {
                return $item->kota_asal . '|' . $item->kota_tujuan;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        $mobils = \App\Models\Mobil::all();
        return view('admin.jadwals.create', compact('rutes', 'mobils'));
    }

    // Menyimpan jadwal baru ke database
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'mobil_id' => 'required|exists:mobils,id',
            'hari_keberangkatan' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam' => 'required',
            'harga' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ], [
            'harga.min' => 'Harga tidak boleh negatif.',
        ]);

        // Validasi status rute
        $rute = \App\Models\Rute::findOrFail($request->rute_id);
        if ($rute->status_rute !== 'aktif') {
            return back()->withErrors(['rute_id' => 'Rute tidak aktif. Status saat ini: ' . $rute->status_rute]);
        }

        // Validasi status mobil
        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        if ($mobil->status !== 'aktif') {
            return back()->withErrors(['mobil_id' => 'Mobil tidak aktif. Status saat ini: ' . $mobil->status]);
        }

        // Cek konflik jadwal mobil - cek berdasarkan hari dan jam
        $conflict = Jadwal::where('mobil_id', $request->mobil_id)
            ->where('hari_keberangkatan', $request->hari_keberangkatan)
            ->where('jam', $request->jam)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['mobil_id' => 'Mobil sudah dijadwalkan di hari dan waktu yang sama.']);
        }

        // Buat jadwal baru
        Jadwal::create([
            'rute_id' => $request->rute_id,
            'mobil_id' => $request->mobil_id,
            'hari_keberangkatan' => $request->hari_keberangkatan,
            'jam' => $request->jam,
            'harga' => $request->harga,
            'is_active' => true,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil ditambahkan dan langsung aktif');
    }

    // Menampilkan form edit jadwal
    public function editJadwal(Jadwal $jadwal)
    {
        // Ambil rute yang unik berdasarkan kombinasi kota asal-tujuan
        $rutes = \App\Models\Rute::all()
            ->groupBy(function ($item) {
                return $item->kota_asal . '|' . $item->kota_tujuan;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        $mobils = \App\Models\Mobil::all();
        return view('admin.jadwals.edit', compact('jadwal', 'rutes', 'mobils'));
    }

    // Update data jadwal di database
    public function updateJadwal(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'mobil_id' => 'required|exists:mobils,id',
            'hari_keberangkatan' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam' => 'required',
            'harga' => 'required|numeric|min:0',
        ]);

        // Validasi status rute
        $rute = \App\Models\Rute::findOrFail($request->rute_id);
        if ($rute->status_rute !== 'aktif') {
            return back()->withErrors(['rute_id' => 'Rute tidak aktif. Status saat ini: ' . $rute->status_rute]);
        }

        // Validasi status mobil
        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        if ($mobil->status !== 'aktif') {
            return back()->withErrors(['mobil_id' => 'Mobil tidak aktif. Status saat ini: ' . $mobil->status]);
        }

        // Cek konflik jadwal jika ada perubahan mobil/hari/jam
        if (
            $request->mobil_id != $jadwal->mobil_id ||
            $request->hari_keberangkatan != $jadwal->hari_keberangkatan ||
            $request->jam != $jadwal->jam
        ) {

            $conflict = Jadwal::where('mobil_id', $request->mobil_id)
                ->where('id', '!=', $jadwal->id)
                ->where('hari_keberangkatan', $request->hari_keberangkatan)
                ->where('jam', $request->jam)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['mobil_id' => 'Mobil sudah dijadwalkan di hari dan waktu yang sama.']);
            }
        }

        // Update jadwal
        $jadwal->update([
            'rute_id' => $request->rute_id,
            'mobil_id' => $request->mobil_id,
            'hari_keberangkatan' => $request->hari_keberangkatan,
            'jam' => $request->jam,
            'harga' => $request->harga,
            'is_active' => $request->has('is_active') ? true : false,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil diperbarui');
    }

    // Toggle status aktif/nonaktif jadwal
    public function toggleJadwalStatus(Jadwal $jadwal)
    {
        $jadwal->update([
            'is_active' => !$jadwal->is_active
        ]);

        $status = $jadwal->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Jadwal berhasil {$status}");
    }

    // Hapus jadwal dari database
    public function destroyJadwal(Jadwal $jadwal)
    {
        if ($jadwal->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jadwal karena ada booking terkait. Hapus booking terlebih dahulu.');
        }

        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus');
    }

    // Menampilkan daftar booking dengan filter pencarian
    public function bookings(Request $request)
    {
        $search  = $request->input('search');
        $status  = $request->input('status');
        $rute_id = $request->input('rute_id');

        $bookings = Booking::with(['user', 'jadwal.rute', 'jadwal.mobil'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas(
                        'user',
                        fn($subQ) =>
                        $subQ->where('name', 'like', "%{$search}%")
                    )
                        ->orWhereHas(
                            'jadwal.rute',
                            fn($subQ) =>
                            $subQ->where('kota_asal', 'like', "%{$search}%")
                                ->orWhere('kota_tujuan', 'like', "%{$search}%")
                        )
                        ->orWhereHas(
                            'jadwal.mobil',
                            fn($subQ) =>
                            $subQ->where('merk', 'like', "%{$search}%")
                                ->orWhere('nomor_polisi', 'like', "%{$search}%")
                        )
                        ->orWhere('jadwal_hari_keberangkatan', 'like', "%{$search}%")
                        ->orWhere('jadwal_jam', 'like', "%{$search}%")
                        ->orWhere('seat_number', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('ticket_number', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($query, $status) => $query->where('status', $status))
            ->when($rute_id, function ($query, $rute_id) {
                $query->whereHas('jadwal', fn($q) => $q->where('rute_id', $rute_id));
            })
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        // Ambil rute untuk filter dropdown
        $rutes = \App\Models\Rute::select('id', 'kota_asal', 'kota_tujuan')
            ->get()
            ->groupBy(function ($item) {
                return $item->kota_asal . '|' . $item->kota_tujuan;
            })
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return view('admin.bookings', compact('bookings', 'search', 'status', 'rute_id', 'rutes'));
    }

    // Menampilkan daftar pelanggan dengan fitur pencarian
    public function pelanggan(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('whatsapp_number', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })->paginate(10);

        return view('admin.pelanggan', compact('users', 'search'));
    }

    // Menampilkan form edit pelanggan
    public function editPelanggan(User $customer)
    {
        return view('admin.pelanggan.edit', compact('customer'));
    }

    // Update data pelanggan di database
    public function updatePelanggan(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|unique:users,whatsapp_number,' . $customer->id,
            'role' => 'required|in:user,admin'
        ]);

        $customer->update($request->only(['name', 'whatsapp_number', 'role']));

        return redirect()->route('admin.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');
    }

    // Menampilkan form tambah pelanggan baru
    public function createPelanggan()
    {
        return view('admin.pelanggan.create');
    }

    // Menyimpan pelanggan baru ke database
    public function storePelanggan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|unique:users,whatsapp_number',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin'
        ]);

        $userData = $request->only(['name', 'whatsapp_number', 'role']);
        $userData['password'] = bcrypt($request->password);

        User::create($userData);

        return redirect()->route('admin.pelanggan')->with('success', 'Berhasil Menambahkan');
    }

    // Hapus pelanggan dari database
    public function destroyPelanggan(User $customer)
    {
        if ($customer->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pelanggan karena ada booking terkait. Hapus booking terlebih dahulu atau ubah status booking.');
        }

        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus');
    }

    // Menampilkan halaman laporan dengan statistik pendapatan
    public function laporan()
    {
        // Hitung total pendapatan keseluruhan
        $totalPendapatan = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->with('jadwal')
            ->get()
            ->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung pendapatan bulan ini
        $pendapatanBulanIni = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung jumlah transaksi selesai
        $transaksiSelesai = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->count();

        // Hitung pendapatan 7 hari terakhir untuk chart
        $pendapatan7Hari = [];
        $labels7Hari = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Hari[] = $date->format('d M');

            $pendapatanHari = Booking::where('status', 'setuju')
                ->where('payment_status', 'sudah_bayar')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->with('jadwal')
                ->get()
                ->sum(function ($booking) {
                    return $booking->jadwal->harga;
                });

            $pendapatan7Hari[] = $pendapatanHari;
        }

        // Hitung pendapatan bulan ini per hari
        $pendapatanBulanIniPerHari = [];
        $labelsBulanIni = [];

        $daysInMonth = now()->daysInMonth;

        $bookingsThisMonth = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->groupBy(function ($booking) {
                return \Carbon\Carbon::parse($booking->created_at)->format('d');
            });

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
            $labelsBulanIni[] = $dayStr;

            $pendapatanHari = $bookingsThisMonth->get($dayStr, collect())->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

            $pendapatanBulanIniPerHari[] = $pendapatanHari;
        }

        return view('admin.laporan', [
            'totalPendapatan' => $totalPendapatan,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'transaksiSelesai' => $transaksiSelesai,
            'pendapatan7Hari' => $pendapatan7Hari,
            'labels7Hari' => $labels7Hari,
            'pendapatanBulanIniPerHari' => $pendapatanBulanIniPerHari,
            'labelsBulanIni' => $labelsBulanIni,
        ]);
    }

    /**
     * Export laporan pendapatan sebagai CSV yang bisa dibuka di Excel
     */
    public function exportLaporan(Request $request)
    {
        $fileName = 'laporan-pendapatan-' . now()->format('Ymd_His') . '.csv';

        $bookings = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->with(['user', 'jadwal.rute'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $columns = [
            'ID',
            'Ticket Number',
            'User Name',
            'Whatsapp',
            'Rute',
            'Tanggal Booking',
            'Jadwal Hari',
            'Jadwal Jam',
            'Harga',
            'Status',
            'Payment Status',
            'Created At'
        ];

        $callback = function () use ($bookings, $columns) {
            $fh = fopen('php://output', 'w');

            // BOM untuk memastikan Excel membaca UTF-8 dengan benar
            fwrite($fh, "\xEF\xBB\xBF");

            fputcsv($fh, $columns);

            foreach ($bookings as $b) {
                $rute = '';
                if ($b->jadwal && $b->jadwal->rute) {
                    $rute = $b->jadwal->rute->kota_asal . ' - ' . $b->jadwal->rute->kota_tujuan;
                }

                $harga = $b->jadwal ? $b->jadwal->harga : '';

                $row = [
                    $b->id,
                    $b->ticket_number,
                    optional($b->user)->name,
                    optional($b->user)->whatsapp_number,
                    $rute,
                    $b->tanggal,
                    $b->jadwal_hari_keberangkatan,
                    $b->jadwal_jam,
                    $harga,
                    $b->status,
                    $b->payment_status,
                    $b->created_at,
                ];

                fputcsv($fh, $row);
            }

            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Menampilkan daftar rute dengan fitur pencarian
    public function rute(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Rute::query();

        if ($search) {
            $query->where('kota_asal', 'like', "%{$search}%")
                ->orWhere('kota_tujuan', 'like', "%{$search}%")
                ->orWhere('jarak_estimasi', 'like', "%{$search}%")
                ->orWhere('harga_tiket', 'like', "%{$search}%")
                ->orWhere('status_rute', 'like', "%{$search}%");
        }

        $rutes = $query->latest()->paginate(10);

        return view('admin.rute', compact('rutes', 'search'));
    }

    // Menampilkan form tambah rute baru
    public function createRute()
    {
        return view('admin.rute.create');
    }

    // Menyimpan rute baru ke database
    public function storeRute(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255|different:kota_asal',
            'jarak_estimasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'jam_keberangkatan' => 'required|date_format:H:i',
            'status_rute' => 'required|string|in:aktif,nonaktif',
        ], [
            'kota_tujuan.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
            'jam_keberangkatan.date_format' => 'Format jam keberangkatan tidak valid.',
        ]);

        \App\Models\Rute::create($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'jam_keberangkatan',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil ditambahkan');
    }

    // Menampilkan form edit rute
    public function editRute(\App\Models\Rute $rute)
    {
        return view('admin.rute.edit', compact('rute'));
    }

    // Update data rute di database
    public function updateRute(Request $request, \App\Models\Rute $rute)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255|different:kota_asal',
            'jarak_estimasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'jam_keberangkatan' => 'required|date_format:H:i',
            'status_rute' => 'required|string|in:aktif,nonaktif',
        ], [
            'kota_tujuan.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
            'jam_keberangkatan.date_format' => 'Format jam keberangkatan tidak valid.',
        ]);

        $rute->update($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'jam_keberangkatan',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil diperbarui');
    }

    // Hapus rute dari database
    public function destroyRute(\App\Models\Rute $rute)
    {
        // Cek apakah ada jadwal terkait
        if ($rute->jadwals()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus rute karena ada jadwal terkait. Hapus jadwal terlebih dahulu.');
        }

        $rute->delete();
        return back()->with('success', 'Rute berhasil dihapus');
    }

    // Menampilkan daftar mobil dengan fitur pencarian
    public function mobil(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Mobil::query();

        if ($search) {
            $query->where('nomor_polisi', 'like', "%{$search}%")
                ->orWhere('jenis', 'like', "%{$search}%")
                ->orWhere('merk', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        $mobils = $query->latest()->paginate(10);

        return view('admin.mobil', compact('mobils', 'search'));
    }

    // Menampilkan form tambah mobil baru
    public function createMobil()
    {
        return view('admin.mobil.create');
    }

    // Menyimpan mobil baru ke database
    public function storeMobil(Request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required|string|unique:mobils|regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/i',
            'jenis' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:100',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'merk' => 'required|string|max:255',
            'status' => 'required|string|in:aktif,tidak aktif',
        ], [
            'nomor_polisi.regex' => 'Format nomor polisi tidak valid (contoh: B 1234 XYZ).',
            'kapasitas.max' => 'Kapasitas maksimal 100 orang.',
        ]);

        \App\Models\Mobil::create($request->only([
            'nomor_polisi',
            'jenis',
            'kapasitas',
            'tahun',
            'merk',
            'status',
        ]));

        return redirect()->route('admin.mobil')->with('success', 'Mobil berhasil ditambahkan');
    }

    // Menampilkan form edit mobil
    public function editMobil(\App\Models\Mobil $mobil)
    {
        return view('admin.mobil.edit', compact('mobil'));
    }

    // Update data mobil di database
    public function updateMobil(Request $request, \App\Models\Mobil $mobil)
    {
        $request->validate([
            'nomor_polisi' => 'required|string|unique:mobils,nomor_polisi,' . $mobil->id . '|regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{1,3}$/i',
            'jenis' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:100',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'merk' => 'required|string|max:255',
            'status' => 'required|string|in:aktif,tidak aktif',
        ], [
            'nomor_polisi.regex' => 'Format nomor polisi tidak valid (contoh: B 1234 XYZ).',
            'kapasitas.max' => 'Kapasitas maksimal 100 orang.',
        ]);

        $mobil->update($request->only([
            'nomor_polisi',
            'jenis',
            'kapasitas',
            'tahun',
            'merk',
            'status',
        ]));

        return redirect()->route('admin.mobil')->with('success', 'Mobil berhasil diperbarui');
    }

    // Hapus mobil dari database
    public function destroyMobil(\App\Models\Mobil $mobil)
    {
        if ($mobil->supir()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus mobil karena ada supir terkait. Hapus atau pindahkan supir terlebih dahulu.');
        }

        if ($mobil->jadwals()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus mobil karena ada jadwal terkait. Hapus jadwal terlebih dahulu.');
        }

        $mobil->delete();
        return back()->with('success', 'Mobil berhasil dihapus');
    }

    // Menampilkan daftar supir dengan fitur pencarian
    public function supir(Request $request)
    {
        $search = $request->input('search');

        $query = \App\Models\Supir::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('telepon', 'like', "%{$search}%")
                ->orWhereHas('mobil', function ($q) use ($search) {
                    $q->where('merk', 'like', "%{$search}%")
                        ->orWhere('nomor_polisi', 'like', "%{$search}%");
                });
        }

        $supirs = $query->with('mobil')->latest()->paginate(10);

        return view('admin.supir', compact('supirs', 'search'));
    }

    // Menampilkan form tambah supir baru
    public function createSupir()
    {
        $mobils = \App\Models\Mobil::whereDoesntHave('supir')->get();
        return view('admin.supir.create', compact('mobils'));
    }

    // Menyimpan supir baru ke database
    public function storeSupir(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'mobil_id' => 'required|exists:mobils,id|unique:supirs,mobil_id',
        ]);

        \App\Models\Supir::create($request->only([
            'nama',
            'telepon',
            'mobil_id',
        ]));

        return redirect()->route('admin.supir')->with('success', 'Supir berhasil ditambahkan');
    }

    // Menampilkan form edit supir
    public function editSupir(\App\Models\Supir $supir)
    {
        $mobils = \App\Models\Mobil::whereDoesntHave('supir')
            ->orWhere('id', $supir->mobil_id)
            ->get();
        return view('admin.supir.edit', compact('supir', 'mobils'));
    }

    // Update data supir di database
    public function updateSupir(Request $request, \App\Models\Supir $supir)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'mobil_id' => 'required|exists:mobils,id|unique:supirs,mobil_id,' . $supir->id,
        ]);

        $supir->update($request->only([
            'nama',
            'telepon',
            'mobil_id',
        ]));

        return redirect()->route('admin.supir')->with('success', 'Supir berhasil diperbarui');
    }

    // Hapus supir dari database
    public function destroySupir(\App\Models\Supir $supir)
    {
        // Cek apakah mobilnya masih punya jadwal aktif
        if ($supir->mobil && $supir->mobil->jadwals()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus supir karena mobilnya masih memiliki jadwal. Hapus jadwal terlebih dahulu.');
        }

        $supir->delete();
        return back()->with('success', 'Supir berhasil dihapus');
    }

    // Update status booking
    public function updateBooking(Request $request, Booking $booking)
    {
        Log::info('Update booking called', [
            'booking_id' => $booking->id,
            'current_status' => $booking->status,
            'current_payment_status' => $booking->payment_status,
            'requested_status' => $request->input('status'),
            'admin_id' => Auth::id()
        ]);

        $request->validate([
            'status' => 'required|in:pending,setuju,batal'
        ]);

        if ($booking->payment_status === 'sudah_bayar') {
            Log::warning('Attempt to modify paid booking blocked', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);
            return back()->with('error', 'Tidak dapat mengubah status booking yang sudah dibayar. Kelola di menu Pembayaran.');
        }

        $oldStatus = $booking->status;

        try {
            $booking->update(['status' => $request->status]);

            if ($request->status === 'setuju' && is_null($booking->payment_status)) {
                $booking->update(['payment_status' => 'belum_bayar']);
            }

            Log::info('Booking status updated successfully', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $booking->status,
                'admin_id' => Auth::id(),
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update booking status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::id()
            ]);
            return back()->with('error', 'Gagal memperbarui status booking: ' . $e->getMessage());
        }

        if ($oldStatus !== $request->status) {
            try {
                $fonnteService = app(\App\Services\FonnteService::class);
                $fonnteService->notifyBookingStatusUpdate($booking);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim Fonnte notifikasi: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status booking diperbarui!');
    }

    // Menampilkan daftar pembayaran dengan filter pencarian
    public function pembayaran(Request $request)
    {
        $search = $request->input('search');
        $payment_status = $request->input('payment_status');

        $bookings = Booking::with(['user', 'jadwal.rute', 'jadwal.mobil'])
            ->where('status', 'setuju')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('jadwal.rute', function ($q) use ($search) {
                        $q->where('kota_asal', 'like', "%{$search}%")
                            ->orWhere('kota_tujuan', 'like', "%{$search}%");
                    })
                    // booking.tanggal exists, search at booking level instead of jadwal.tanggal
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%");
            })
            ->when($payment_status, function ($query, $payment_status) {
                return $query->where('payment_status', $payment_status);
            })
            ->latest()->paginate(10);

        return view('admin.pembayaran', compact('bookings', 'search', 'payment_status'));
    }

    // Update status pembayaran booking
    public function updatePembayaran(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:belum_bayar,sudah_bayar'
        ]);

        $booking->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Status pembayaran diperbarui!');
    }

    // Mendapatkan jam keberangkatan berdasarkan rute
    public function getJamKeberangkatan($ruteId)
    {
        try {
            $rute = \App\Models\Rute::findOrFail($ruteId);

            $jamKeberangkatan = \App\Models\Rute::where('kota_asal', $rute->kota_asal)
                ->where('kota_tujuan', $rute->kota_tujuan)
                ->where('status_rute', 'aktif')
                ->whereNotNull('jam_keberangkatan')
                ->pluck('jam_keberangkatan')
                ->unique()
                ->sort()
                ->values();

            return response()->json([
                'success' => true,
                'jam_keberangkatan' => $jamKeberangkatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rute tidak ditemukan'
            ], 404);
        }
    }

    // Mendapatkan data rute berdasarkan ID
    public function getRuteData($ruteId)
    {
        try {
            $rute = \App\Models\Rute::findOrFail($ruteId);

            return response()->json([
                'success' => true,
                'harga_tiket' => $rute->harga_tiket,
                'jam_keberangkatan' => [$rute->jam_keberangkatan],
                'kota_asal' => $rute->kota_asal,
                'kota_tujuan' => $rute->kota_tujuan,
                'status_rute' => $rute->status_rute
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rute tidak ditemukan'
            ], 404);
        }
    }
}
