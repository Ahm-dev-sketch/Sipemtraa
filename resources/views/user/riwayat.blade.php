@extends('layouts.app')

@section('content')
    <!-- Header Section: Bagian header halaman riwayat pemesanan -->
    <div class="mb-8 fade-down animate-on-scroll">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-1 h-8 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-600 rounded-full"></div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Riwayat <span
                    class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Pemesanan</span>
            </h2>
        </div>
        <p class="text-gray-600 ml-7">Kelola dan pantau semua pemesanan tiket perjalanan Anda</p>
    </div>

    <!-- Search and Filter Form: Form pencarian dan filter riwayat pemesanan -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8 fade-right animate-on-scroll">
        <form method="GET" action="{{ route('riwayat') }}" class="flex flex-col gap-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Cari nomor tiket, kota, atau nama penumpang..."
                    class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-gray-50 hover:bg-white transition-all duration-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-list-check text-gray-400"></i>
                    </div>
                    <select name="status" id="status"
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-gray-50 hover:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Status Booking</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="setuju" {{ request('status') == 'setuju' ? 'selected' : '' }}>✅ Disetujui</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-wallet text-gray-400"></i>
                    </div>
                    <select name="payment_status" id="payment_status"
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm bg-gray-50 hover:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Status Pembayaran</option>
                        <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>💰
                            Sudah Bayar</option>
                        <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>⏰
                            Belum Bayar</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 group px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-semibold text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('riwayat') }}"
                        class="px-4 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 font-semibold text-sm flex items-center justify-center">
                        <i class="fas fa-rotate-right"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Empty State: Tampilan ketika tidak ada riwayat pemesanan -->
    @if ($bookings->isEmpty())
        <div class="fade-up animate-on-scroll">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pemesanan</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pemesanan tiket.</p>
                <a href="{{ route('jadwal') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600
                        text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300
                        shadow-lg hover:shadow-xl hover:scale-105 font-semibold">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Lihat Jadwal</span>
                </a>
            </div>
        </div>
    @else
        <!-- Bookings List: Daftar riwayat pemesanan -->
        <div class="space-y-6 fade-up animate-on-scroll">
            @foreach ($bookings as $booking)
                <!-- Booking Card: Kartu informasi pemesanan -->
                <div
                    class="group bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 overflow-hidden hover:border-blue-200">

                    <!-- Booking Header: Header kartu pemesanan dengan status -->
                    <div
                        class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3
                        {{ $booking->status == 'setuju' && $booking->payment_status == 'sudah_bayar' ? 'bg-gradient-to-r from-green-50 to-emerald-50' : '' }}
                        {{ $booking->status == 'pending' ? 'bg-gradient-to-r from-yellow-50 to-amber-50' : '' }}
                        {{ $booking->status == 'batal' ? 'bg-gradient-to-r from-red-50 to-rose-50' : '' }}
                        {{ $booking->status == 'setuju' && $booking->payment_status == 'belum_bayar' ? 'bg-gradient-to-r from-blue-50 to-indigo-50' : '' }}">

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-ticket-alt text-blue-600"></i>
                                <span class="font-bold text-gray-900">{{ $booking->ticket_number }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <i class="far fa-clock"></i>
                                <span>Dipesan
                                    <!-- Format waktu relatif menggunakan Carbon: Menampilkan waktu relatif dalam bahasa Indonesia -->
                                    {{ \Carbon\Carbon::parse($booking->created_at)->locale('id')->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div>
                            @if ($booking->status == 'setuju' && $booking->payment_status == 'sudah_bayar')
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-lg">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Lunas</span>
                                </span>
                            @elseif($booking->status == 'setuju' && $booking->payment_status == 'belum_bayar')
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-lg">
                                    <i class="fas fa-clock"></i>
                                    <span>Menunggu Pembayaran</span>
                                </span>
                            @elseif($booking->status == 'pending')
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-lg">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>Pending</span>
                                </span>
                            @else
                                <span
                                    class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-lg">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Dibatalkan</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Details: Detail lengkap pemesanan -->
                    <div class="p-6 md:p-8">
                        <!-- Route Info Section: Informasi rute perjalanan -->
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></div>
                                <h4 class="font-bold text-gray-900">Informasi Rute Perjalanan</h4>
                            </div>

                            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 mb-4">
                                <div class="flex-1">
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border-2 border-blue-200 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-600 font-medium mb-0.5">Keberangkatan Dari</p>
                                                <p class="text-xl font-bold text-gray-900">
                                                    {{ $booking->jadwal->rute->kota_asal ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex md:flex-col items-center justify-center gap-2 px-4 py-2 md:py-0">
                                    <div class="flex md:flex-col items-center gap-2">
                                        <div
                                            class="w-12 md:w-0.5 h-0.5 md:h-6 bg-gradient-to-r md:bg-gradient-to-b from-blue-400 to-indigo-400">
                                        </div>
                                        <div
                                            class="shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-bus text-white text-sm"></i>
                                        </div>
                                        <div
                                            class="w-12 md:w-0.5 h-0.5 md:h-6 bg-gradient-to-r md:bg-gradient-to-b from-indigo-400 to-purple-400">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
                                        Langsung
                                    </span>
                                </div>

                                <div class="flex-1">
                                    <div
                                        class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border-2 border-purple-200 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                                <i class="fas fa-flag-checkered text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-600 font-medium mb-0.5">Tujuan Ke</p>
                                                <p class="text-xl font-bold text-gray-900">
                                                    {{ $booking->jadwal->rute->kota_tujuan ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-road text-blue-600"></i>
                                    <span>Tanpa Transit</span>
                                </div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-green-600"></i>
                                    <span>Aman & Nyaman</span>
                                </div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-indigo-600"></i>
                                    <span>Supir Profesional</span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Info Grid: Grid informasi pemesanan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Jadwal Keberangkatan</p>
                                        <p class="font-semibold text-gray-900">
                                            <!-- Format tanggal lengkap menggunakan Carbon: Menampilkan hari, tanggal, bulan, tahun dalam bahasa Indonesia -->
                                            {{ \Carbon\Carbon::parse($booking->jadwal_tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                        </p>
                                        <p class="text-sm text-blue-600 font-medium">Pukul {{ $booking->jadwal_jam }} WIB
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-bus text-indigo-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Jenis Kendaraan</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->jadwal->mobil->jenis ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-tie text-purple-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Supir</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $booking->jadwal->mobil->supir->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Nama Penumpang</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->user->name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chair text-yellow-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Nomor Kursi</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->seat_number ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 {{ $booking->payment_status == 'sudah_bayar' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                        <i
                                            class="fas fa-wallet {{ $booking->payment_status == 'sudah_bayar' ? 'text-green-600' : 'text-red-600' }}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-600 mb-0.5">Status Pembayaran</p>
                                        <p
                                            class="font-semibold {{ $booking->payment_status == 'sudah_bayar' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $booking->payment_status == 'sudah_bayar' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Footer: Footer kartu dengan total harga dan aksi -->
                    <div
                        class="px-6 md:px-8 py-6 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-tag text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-0.5">Total Pembayaran</p>
                                <p
                                    class="text-2xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    <!-- Format harga dengan number_format: Menampilkan harga dalam format Rupiah -->
                                    Rp {{ number_format($booking->jadwal->harga ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2 w-full md:w-auto">
                            @if ($booking->status == 'setuju' && $booking->payment_status == 'sudah_bayar')
                                <a href="{{ route('booking.download.ticket', $booking) }}" target="_blank"
                                    class="group flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-semibold">
                                    <i class="fas fa-download"></i>
                                    <span>Download E-Ticket</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @elseif($booking->status === 'pending')
                                <form action="{{ route('booking.cancel', $booking->id) }}" method="POST"
                                    class="cancel-booking-form flex-1 md:flex-none">
                                    @csrf
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-semibold">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Batalkan Pesanan</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination: Navigasi halaman untuk riwayat pemesanan -->
        <div class="mt-8 flex justify-center fade-up animate-on-scroll">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-2">
                {{ $bookings->links('vendor.pagination.compact') }}
            </div>
        </div>
    @endif
@endsection
