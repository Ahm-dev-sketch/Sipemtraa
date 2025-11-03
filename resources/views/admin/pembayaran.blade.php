@extends('layouts.app')

@section('page-title', 'Kelola Pembayaran')
@section('page-subtitle', 'Monitor dan konfirmasi pembayaran tiket')

@section('content')
    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.pembayaran') }}" class="flex flex-col md:flex-row gap-3">
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
                        placeholder="Cari nama pelanggan atau nomor tiket..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
            </div>



            <!-- Payment Status Filter -->
            <div class="w-full md:w-44">
                <select name="payment_status" id="payment_status"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Status Pembayaran</option>
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
                <a href="{{ route('admin.pembayaran') }}"
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

    {{-- Desktop View - Table --}}
    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ticket
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rute &
                            Jadwal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kursi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status Booking</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Pembayaran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-ticket-alt text-orange-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $booking->ticket_number ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-route text-purple-600"></i>
                                    <span class="font-medium text-gray-900">{{ $booking->jadwal->rute->kota_asal }}</span>
                                    <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                    <span class="font-medium text-gray-900">{{ $booking->jadwal->rute->kota_tujuan }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-calendar-day"></i>
                                        {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-clock"></i>
                                        {{ $booking->jadwal->jam }} WIB
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    <i class="fas fa-chair text-xs mr-1"></i>
                                    {{ $booking->seat_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center justify-center gap-1
                                {{ $booking->status == 'setuju'
                                    ? 'bg-green-100 text-green-700'
                                    : ($booking->status == 'batal'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                    @if ($booking->status == 'setuju')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($booking->status == 'pending')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center border border-white">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->payment_status == 'sudah_bayar' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $booking->payment_status == 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border border-white">
                                <form action="{{ route('admin.pembayaran.update', $booking) }}" method="POST"
                                    class="flex items-center justify-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="payment_status" id="payment_status_{{ $booking->id }}"
                                        value="">
                                    @if ($booking->payment_status !== 'sudah_bayar')
                                        <button type="submit"
                                            onclick="document.getElementById('payment_status_{{ $booking->id }}').value='sudah_bayar'"
                                            class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 transition">
                                            Sudah Bayar
                                        </button>
                                    @else
                                        <a href="{{ route('booking.download.ticket', $booking) }}"
                                            class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-3 text-center text-gray-500 border border-white">
                                Tidak ada data pembayaran ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile View - Cards --}}
        <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
            @forelse($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                    {{-- Header with Ticket Number --}}
                    <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                        <div>
                            <div class="text-xs text-gray-500">Ticket Number</div>
                            <div class="font-semibold text-blue-600">{{ $booking->ticket_number ?? 'N/A' }}</div>
                        </div>
                        <div class="flex flex-col gap-1 items-end">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1
                            {{ $booking->status == 'setuju' ? 'bg-green-100 text-green-700' : ($booking->status == 'batal' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                @if ($booking->status == 'setuju')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @elseif($booking->status == 'pending')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                                {{ ucfirst($booking->status) }}
                            </span>
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $booking->payment_status == 'sudah_bayar' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $booking->payment_status == 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                            </span>
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="space-y-2 mb-3">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-gray-600">Pelanggan:</span>
                            <span class="font-medium text-gray-900">{{ $booking->user->name }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600">Rute:</span>
                            <span class="font-medium text-gray-900 text-xs">
                                {{ $booking->jadwal->rute->kota_asal }} → {{ $booking->jadwal->rute->kota_tujuan }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Jam:</span>
                            <span class="font-medium text-gray-900">{{ $booking->jadwal->jam }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="text-gray-600">Kursi:</span>
                            <span class="font-medium text-gray-900">{{ $booking->seat_number }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3 border-t border-gray-100">
                        <form action="{{ route('admin.pembayaran.update', $booking) }}" method="POST"
                            class="flex items-center justify-center gap-2">
                            @csrf @method('PUT')
                            <input type="hidden" name="payment_status" id="payment_status_mobile_{{ $booking->id }}"
                                value="">
                            @if ($booking->payment_status !== 'sudah_bayar')
                                <button type="submit"
                                    onclick="document.getElementById('payment_status_mobile_{{ $booking->id }}').value='sudah_bayar'"
                                    class="flex-1 bg-green-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-green-700 transition inline-flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Sudah Bayar
                                </button>
                            @else
                                <a href="{{ route('booking.download.ticket', $booking) }}"
                                    class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Download Tiket
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 text-center text-gray-500">
                    Tidak ada data pembayaran ditemukan.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-end">
            {{ $bookings->links() }}
        </div>
    @endsection
