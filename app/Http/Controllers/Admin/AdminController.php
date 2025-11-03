<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        // Hitung total pendapatan bulan ini dari booking yang disetujui dan sudah bayar
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

        // Hitung perjalanan aktif (jadwal yang tanggalnya >= hari ini)
        $perjalananAktif = Jadwal::where('tanggal', '>=', now()->format('Y-m-d'))
            ->count();

        // Hitung total pelanggan (hanya user, bukan admin)
        $totalPelanggan = User::where('role', 'user')->count();

        // Hitung pendapatan 7 hari terakhir untuk chart (hanya yang sudah bayar)
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

    // Kelola Jadwal (list)
    public function jadwals(Request $request)
    {
        $search = $request->input('search');

        $jadwals = Jadwal::with('rute')
            ->when($search, function ($query, $search) {
                return $query->whereHas('rute', function ($q) use ($search) {
                    $q->where('kota_asal', 'like', "%{$search}%")
                        ->orWhere('kota_tujuan', 'like', "%{$search}%");
                })
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhere('jam', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%");
            })->latest()->paginate(10);

        return view('admin.jadwals', compact('jadwals', 'search'));
    }

    // Form tambah jadwal
    public function createJadwal()
    {
        $rutes = \App\Models\Rute::all();
        $mobils = \App\Models\Mobil::all();
        return view('admin.jadwals.create', compact('rutes', 'mobils'));
    }

    // Simpan jadwal baru
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required',
            'harga' => 'required|integer|min:0',
            'day_offset' => 'nullable|integer|min:0|max:7',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ], [
            'tanggal.after_or_equal' => 'Tanggal jadwal harus hari ini atau setelahnya.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'day_offset.max' => 'Offset maksimal 7 hari ke depan.',
        ]);

        // CRITICAL FIX: Validasi status rute
        $rute = \App\Models\Rute::findOrFail($request->rute_id);
        if ($rute->status_rute !== 'aktif') {
            return back()->withErrors(['rute_id' => 'Rute tidak aktif. Status saat ini: ' . $rute->status_rute]);
        }

        // CRITICAL FIX: Validasi status mobil
        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        if ($mobil->status !== 'aktif') {
            return back()->withErrors(['mobil_id' => 'Mobil tidak aktif. Status saat ini: ' . $mobil->status]);
        }

        // CRITICAL FIX: Cek konflik jadwal untuk mobil yang sama
        $conflict = Jadwal::where('mobil_id', $request->mobil_id)
            ->where('tanggal', $request->tanggal)
            ->where('jam', $request->jam)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['mobil_id' => 'Mobil sudah dijadwalkan di waktu yang sama.']);
        }

        Jadwal::create([
            'rute_id' => $request->rute_id,
            'mobil_id' => $request->mobil_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'harga' => $request->harga,
            'day_offset' => (string)($request->day_offset ?? 0),
            'is_active' => $request->has('is_active') ? true : false,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Form edit jadwal
    public function editJadwal(Jadwal $jadwal)
    {
        $rutes = \App\Models\Rute::all();
        $mobils = \App\Models\Mobil::all();
        return view('admin.jadwals.edit', compact('jadwal', 'rutes', 'mobils'));
    }

    // Update jadwal
    public function updateJadwal(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'rute_id' => 'required|exists:rutes,id',
            'mobil_id' => 'required|exists:mobils,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'harga' => 'required|integer|min:0',
            'day_offset' => 'nullable|integer|min:0|max:7',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ], [
            'harga.min' => 'Harga tidak boleh negatif.',
            'day_offset.max' => 'Offset maksimal 7 hari ke depan.',
        ]);

        // CRITICAL FIX: Validasi status rute
        $rute = \App\Models\Rute::findOrFail($request->rute_id);
        if ($rute->status_rute !== 'aktif') {
            return back()->withErrors(['rute_id' => 'Rute tidak aktif. Status saat ini: ' . $rute->status_rute]);
        }

        // CRITICAL FIX: Validasi status mobil
        $mobil = \App\Models\Mobil::findOrFail($request->mobil_id);
        if ($mobil->status !== 'aktif') {
            return back()->withErrors(['mobil_id' => 'Mobil tidak aktif. Status saat ini: ' . $mobil->status]);
        }

        // CRITICAL FIX: Cek konflik jadwal jika mobil, tanggal, atau jam berubah
        if (
            $request->mobil_id != $jadwal->mobil_id ||
            $request->tanggal != $jadwal->tanggal ||
            $request->jam != $jadwal->jam
        ) {

            $conflict = Jadwal::where('mobil_id', $request->mobil_id)
                ->where('id', '!=', $jadwal->id) // Exclude current jadwal
                ->where('tanggal', $request->tanggal)
                ->where('jam', $request->jam)
                ->exists();

            if ($conflict) {
                return back()->withErrors(['mobil_id' => 'Mobil sudah dijadwalkan di waktu yang sama.']);
            }
        }

        $jadwal->update([
            'rute_id' => $request->rute_id,
            'mobil_id' => $request->mobil_id,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'harga' => $request->harga,
            'day_offset' => (string)($request->day_offset ?? 0),
            'is_active' => $request->has('is_active') ? true : false,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.jadwals')->with('success', 'Jadwal berhasil diperbarui');
    }

    // Toggle status jadwal (aktif/nonaktif)
    public function toggleJadwalStatus(Jadwal $jadwal)
    {
        $jadwal->update([
            'is_active' => !$jadwal->is_active
        ]);

        $status = $jadwal->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Jadwal berhasil {$status}");
    }

    // Hapus jadwal
    public function destroyJadwal(Jadwal $jadwal)
    {
        // Cek apakah ada booking terkait
        if ($jadwal->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jadwal karena ada booking terkait. Hapus booking terlebih dahulu.');
        }

        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus');
    }

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
                        // gunakan kolom dari tabel bookings
                        ->orWhere('jadwal_tanggal', 'like', "%{$search}%")
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

        $rutes = \App\Models\Rute::select('id', 'kota_asal', 'kota_tujuan')->get();

        return view('admin.bookings', compact('bookings', 'search', 'status', 'rute_id', 'rutes'));
    }

    // Kelola Pelanggan
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

    // Form edit pelanggan
    public function editPelanggan(User $customer)
    {
        return view('admin.pelanggan.edit', compact('customer'));
    }

    // Update pelanggan
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

    // Form tambah pelanggan
    public function createPelanggan()
    {
        return view('admin.pelanggan.create');
    }

    // Simpan pelanggan baru
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

    // Hapus pelanggan
    public function destroyPelanggan(User $customer)
    {
        // Cek apakah ada booking terkait
        if ($customer->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pelanggan karena ada booking terkait. Hapus booking terlebih dahulu atau ubah status booking.');
        }

        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus');
    }

    // Laporan pendapatan
    public function laporan()
    {
        // Hitung total pendapatan (hanya yang sudah bayar)
        $totalPendapatan = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->with('jadwal')
            ->get()
            ->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung pendapatan bulan ini (hanya yang sudah bayar)
        $pendapatanBulanIni = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with('jadwal')
            ->get()
            ->sum(function ($booking) {
                return $booking->jadwal->harga;
            });

        // Hitung transaksi selesai (hanya yang sudah bayar)
        $transaksiSelesai = Booking::where('status', 'setuju')
            ->where('payment_status', 'sudah_bayar')
            ->count();

        // Hitung pendapatan 7 hari terakhir untuk chart (hanya yang sudah bayar)
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

        // Hitung pendapatan bulan ini per hari untuk pie chart
        $pendapatanBulanIniPerHari = [];
        $labelsBulanIni = [];

        $daysInMonth = now()->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = now()->setDay($day);
            $labelsBulanIni[] = $date->format('d');

            $pendapatanHari = Booking::where('status', 'setuju')
                ->where('payment_status', 'sudah_bayar')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->with('jadwal')
                ->get()
                ->sum(function ($booking) {
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

    // Data Rute
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

    // Form tambah rute
    public function createRute()
    {
        return view('admin.rute.create');
    }

    // Simpan rute baru
    public function storeRute(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255|different:kota_asal',
            'jarak_estimasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'status_rute' => 'required|string|in:aktif,nonaktif',
        ], [
            'kota_tujuan.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
        ]);

        \App\Models\Rute::create($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil ditambahkan');
    }

    // Form edit rute
    public function editRute(\App\Models\Rute $rute)
    {
        return view('admin.rute.edit', compact('rute'));
    }

    // Update rute
    public function updateRute(Request $request, \App\Models\Rute $rute)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255|different:kota_asal',
            'jarak_estimasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'status_rute' => 'required|string|in:aktif,nonaktif',
        ], [
            'kota_tujuan.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka.',
        ]);

        $rute->update($request->only([
            'kota_asal',
            'kota_tujuan',
            'jarak_estimasi',
            'harga_tiket',
            'status_rute',
        ]));

        return redirect()->route('admin.rute')->with('success', 'Rute berhasil diperbarui');
    }

    // Hapus rute
    public function destroyRute(\App\Models\Rute $rute)
    {
        // Cek apakah ada jadwal terkait
        if ($rute->jadwals()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus rute karena ada jadwal terkait. Hapus jadwal terlebih dahulu.');
        }

        $rute->delete();
        return back()->with('success', 'Rute berhasil dihapus');
    }

    // Data Mobil
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

    // Form tambah mobil
    public function createMobil()
    {
        return view('admin.mobil.create');
    }

    // Simpan mobil baru
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

    // Form edit mobil
    public function editMobil(\App\Models\Mobil $mobil)
    {
        return view('admin.mobil.edit', compact('mobil'));
    }

    // Update mobil
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

    // Hapus mobil
    public function destroyMobil(\App\Models\Mobil $mobil)
    {
        // Cek apakah ada jadwal terkait
        if ($mobil->jadwals()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus mobil karena ada jadwal terkait. Hapus jadwal terlebih dahulu.');
        }

        // Cek apakah ada supir terkait
        if ($mobil->supir()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus mobil karena ada supir terkait. Hapus supir terlebih dahulu.');
        }

        $mobil->delete();
        return back()->with('success', 'Mobil berhasil dihapus');
    }

    // Data Supir
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

    // Form tambah supir
    public function createSupir()
    {
        $mobils = \App\Models\Mobil::whereDoesntHave('supir')->get();
        return view('admin.supir.create', compact('mobils'));
    }

    // Simpan supir baru
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

    // Form edit supir
    public function editSupir(\App\Models\Supir $supir)
    {
        $mobils = \App\Models\Mobil::whereDoesntHave('supir')
            ->orWhere('id', $supir->mobil_id)
            ->get();
        return view('admin.supir.edit', compact('supir', 'mobils'));
    }

    // Update supir
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

    // Hapus supir
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

        // CRITICAL FIX: Prevent status change if payment already processed
        if ($booking->payment_status === 'sudah_bayar') {
            Log::warning('Attempt to modify paid booking blocked', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);
            return back()->with('error', 'Tidak dapat mengubah status booking yang sudah dibayar. Kelola di menu Pembayaran.');
        }

        $oldStatus = $booking->status;

        try {
            // Update status untuk semua kasus (setuju, pending, atau batal)
            $booking->update(['status' => $request->status]);

            // Jika status diubah menjadi setuju, ubah payment_status menjadi belum_bayar (default)
            if ($request->status === 'setuju') {
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

        // Kirim notifikasi whatsapp jika status berubah
        if ($oldStatus !== $request->status) {
            try {
                $fonnteService = app(\App\Services\FonnteService::class);
                $fonnteService->notifyBookingStatusUpdate($booking);
            } catch (\Exception $e) {
                // Log error jika whatsapp gagal dikirim, tapi tetap lanjutkan proses
                Log::error('Gagal mengirim Fonnte notifikasi: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Status booking diperbarui!');
    }

    // Kelola Pembayaran
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
                    ->orWhereHas('jadwal', function ($q) use ($search) {
                        $q->where('tanggal', 'like', "%{$search}%");
                    })
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%");
            })
            ->when($payment_status, function ($query, $payment_status) {
                return $query->where('payment_status', $payment_status);
            })
            ->latest()->paginate(10);

        return view('admin.pembayaran', compact('bookings', 'search', 'payment_status'));
    }

    // Update status pembayaran
    public function updatePembayaran(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:belum_bayar,sudah_bayar'
        ]);

        $booking->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Status pembayaran diperbarui!');
    }
}
