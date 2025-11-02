@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 fade-down animate-on-scroll">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 17v-6a2 2 0 012-2h8m-8 0V7a2 2 0 012-2h2a2 2 0 012 2v2m0 4h2a2 2 0 012 2v6M9 17h6" />
        </svg>
        Kelola Booking
    </h2>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6 fade-up animate-on-scroll">
        <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col md:flex-row gap-3">
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

            <!-- Status Filter -->
            <div class="w-full md:w-40">
                <select name="status" id="status"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="setuju" {{ request('status') == 'setuju' ? 'selected' : '' }}>Disetujui</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Route Filter -->
            <div class="w-full md:w-48">
                <select name="rute_id" id="rute_id"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Semua Rute</option>
                    @foreach ($rutes ?? [] as $rute)
                        <option value="{{ $rute->id }}" {{ request('rute_id') == $rute->id ? 'selected' : '' }}>
                            {{ $rute->kota_asal }} - {{ $rute->kota_tujuan }}
                        </option>
                    @endforeach
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
                <a href="{{ route('admin.bookings') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2.5 rounded-lg text-sm font-medium flex items-center transition-colors cursor-pointer">
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
    <div class="hidden lg:block overflow-x-auto bg-white p-6 rounded-lg shadow fade-up animate-on-scroll">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-6 py-3 text-center border border-white">User</th>
                    <th class="px-6 py-3 text-center border border-white">Kota Awal</th>
                    <th class="px-6 py-3 text-center border border-white">Kota Tujuan</th>
                    <th class="px-6 py-3 text-center border border-white">Tanggal</th>
                    <th class="px-6 py-3 text-center border border-white">Jam</th>
                    <th class="px-6 py-3 text-center border border-white">Mobil</th>
                    <th class="px-6 py-3 text-center border border-white">Kursi</th>
                    <th class="px-6 py-3 text-center border border-white">Status</th>
                    <th class="px-6 py-3 text-center border border-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->user->name }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_asal : '-' }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_tujuan : '-' }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal_tanggal }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ $booking->jadwal ? $booking->jadwal->jam : '-' }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ $booking->jadwal->mobil ? $booking->jadwal->mobil->merk . ' (' . $booking->jadwal->mobil->nomor_polisi . ')' : '-' }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->seat_number }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center justify-center gap-1
                                {{ $booking->status == 'setuju'
                                    ? 'bg-green-100 text-green-700'
                                    : ($booking->status == 'batal'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                @if ($booking->status == 'setuju')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($booking->status == 'pending')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @endif
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST"
                                class="flex items-center justify-center gap-2">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" id="status_{{ $booking->id }}" value="">
                                @if ($booking->status !== 'batal' && $booking->payment_status !== 'sudah_bayar')
                                    <button type="submit"
                                        onclick="document.getElementById('status_{{ $booking->id }}').value='setuju'"
                                        class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 transition inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setuju
                                    </button>
                                    <button type="submit"
                                        onclick="document.getElementById('status_{{ $booking->id }}').value='batal'"
                                        class="bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700 transition inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Batalkan
                                    </button>
                                @else
                                    <span class="text-gray-500 text-sm">No Action</span>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile View - Cards --}}
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @foreach ($bookings as $booking)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                {{-- User Info --}}
                <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-200">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">{{ $booking->user->name }}</div>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1
                        {{ $booking->status == 'setuju' ? 'bg-green-100 text-green-700' : ($booking->status == 'batal' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        @if ($booking->status == 'setuju')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        @elseif($booking->status == 'pending')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @endif
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                {{-- Booking Details --}}
                <div class="space-y-2 mb-3">
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
                        <span class="font-medium text-gray-900">
                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_asal : '-' }} →
                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_tujuan : '-' }}
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
                        <span class="font-medium text-gray-900">{{ $booking->jadwal_tanggal }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-600">Jam:</span>
                        <span
                            class="font-medium text-gray-900">{{ $booking->jadwal ? $booking->jadwal->jam : '-' }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span class="text-gray-600">Mobil:</span>
                        <span class="font-medium text-gray-900 text-xs">
                            {{ $booking->jadwal->mobil ? $booking->jadwal->mobil->merk . ' (' . $booking->jadwal->mobil->nomor_polisi . ')' : '-' }}
                        </span>
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
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST"
                        class="flex items-center justify-center gap-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" id="status_mobile_{{ $booking->id }}" value="">
                        @if ($booking->status !== 'batal' && $booking->payment_status !== 'sudah_bayar')
                            <button type="submit"
                                onclick="document.getElementById('status_mobile_{{ $booking->id }}').value='setuju'"
                                class="flex-1 bg-green-600 text-white text-sm px-3 py-2 rounded hover:bg-green-700 transition inline-flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setuju
                            </button>
                            <button type="submit"
                                onclick="document.getElementById('status_mobile_{{ $booking->id }}').value='batal'"
                                class="flex-1 bg-red-600 text-white text-sm px-3 py-2 rounded hover:bg-red-700 transition inline-flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batalkan
                            </button>
                        @else
                            <span class="text-gray-500 text-sm">No Action</span>
                        @endif
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-end">
        {{ $bookings->links() }}
    </div>
@endsection
