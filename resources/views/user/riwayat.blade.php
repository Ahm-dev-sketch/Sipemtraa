@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Riwayat Pemesanan Tiket Saya</h2>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('riwayat') }}" class="flex flex-col md:flex-row gap-3">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari nomor tiket atau kota..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-40">
                <select name="status" id="status"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="setuju" {{ request('status') == 'setuju' ? 'selected' : '' }}>Lunas</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div class="w-full md:w-44">
                <select name="payment_status" id="payment_status"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Semua Pembayaran</option>
                    <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah
                        Bayar</option>
                    <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum
                        Bayar</option>
                </select>
            </div>



            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari
                </button>
                <a href="{{ route('riwayat') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2.5 rounded-lg text-sm font-medium flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

    @if ($bookings->isEmpty())
        <p class="text-gray-500" data-aos="fade-up">Belum ada data pesanan.</p>
    @else
        <div class="space-y-6" data-aos="fade-up" data-aos-delay="200">
            @foreach ($bookings as $booking)
                <div class="bg-white rounded shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-semibold text-blue-700">No. Tiket: </span>
                            <a href="#" class="font-semibold text-blue-700 hover:underline">
                                {{ $booking->ticket_number }}
                            </a>
                            <p class="text-gray-500 text-sm">
                                Dipesan pada: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            @if ($booking->status == 'setuju')
                                <span
                                    class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Lunas</span>
                            @elseif($booking->status == 'pending')
                                <span
                                    class="bg-yellow-400 text-white px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            @else
                                <span
                                    class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-gray-700 mb-4">
                        <div>
                            <p><span class="font-semibold">Rute:</span>
                                {{ $booking->jadwal->rute->kota_asal ?? '-' }} —
                                {{ $booking->jadwal->rute->kota_tujuan ?? '-' }}
                            </p>
                            <p><span class="font-semibold">Jadwal:</span>
                                {{ \Carbon\Carbon::parse($booking->jadwal_tanggal)->format('l, d F Y') }}
                                pukul {{ $booking->jadwal_jam }} WIB
                            </p>
                            <p><span class="font-semibold">Jenis Mobil:</span> {{ $booking->jadwal->mobil->jenis ?? '-' }}
                            </p>
                            <p><span class="font-semibold">Supir:</span> {{ $booking->jadwal->mobil->supir->nama ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p><span class="font-semibold">Penumpang:</span> {{ $booking->user->name ?? '-' }}</p>
                            <p><span class="font-semibold">No. Kursi:</span> {{ $booking->seat_number ?? '-' }}</p>
                            <p><span class="font-semibold">Status Pembayaran:</span>
                                @if ($booking->payment_status == 'sudah_bayar')
                                    <span class="text-green-600 font-semibold">Sudah Bayar</span>
                                @else
                                    <span class="text-red-600 font-semibold">Belum Bayar</span>
                                @endif
                            </p>
                            <p><span class="font-semibold">No. Tiket:</span> {{ $booking->ticket_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="font-semibold text-lg text-gray-900">
                            Total: Rp {{ number_format($booking->jadwal->harga ?? 0, 0, ',', '.') }}
                        </div>

                        @if ($booking->status == 'setuju' && $booking->payment_status == 'sudah_bayar')
                            <a href="{{ route('booking.download.ticket', $booking) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                Download E-Ticket
                            </a>
                        @elseif($booking->status === 'pending')
                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST"
                                class="cancel-booking-form">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-end w-full pr-4" data-aos="fade-up" data-aos-delay="400">
            {{ $bookings->links() }}
        </div>
    @endif
@endsection
