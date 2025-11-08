@extends('layouts.app')

@section('page-title', 'Data Pemesanan')

@section('page-subtitle', 'Kelola dan monitor booking pelanggan')

@section('content')
    <!-- Search and Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

        <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col md:flex-row gap-3">
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
            <div class="w-full md:w-40">
                <select name="status" id="status"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="setuju" {{ request('status') == 'setuju' ? 'selected' : '' }}>Disetujui</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
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

    <!-- Desktop Table View -->
    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rute
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jadwal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mobil
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kursi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded flex items-center justify-center">
                                        <i class="fas fa-route text-purple-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900">
                                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_asal : '-' }}</div>
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            {{ $booking->jadwal->rute ? $booking->jadwal->rute->kota_tujuan : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    // Determine a display date for the booking: prefer stored booking->tanggal (actual booked date),
                                    // otherwise compute next occurrence from jadwal->getUpcomingDates(). Fallback to stored
                                    // booking hari label if nothing available.
                                    $displayDate = null;
                                    if (!empty($booking->tanggal)) {
                                        try {
                                            $displayDate = \Carbon\Carbon::parse($booking->tanggal);
                                        } catch (\Exception $e) {
                                            $displayDate = null;
                                        }
                                    }

                                    if (!$displayDate && $booking->jadwal) {
                                        try {
                                            $upcoming = $booking->jadwal->getUpcomingDates(4);
                                            if ($upcoming && $upcoming->count() > 0) {
                                                $displayDate = $upcoming->first();
                                            }
                                        } catch (\Exception $e) {
                                            // ignore
                                        }
                                    }
                                @endphp

                                <div class="text-sm font-medium text-gray-900">
                                    @if ($displayDate)
                                        {{ $displayDate->locale('id')->isoFormat('dddd') }}
                                    @else
                                        {{ $booking->jadwal_hari_keberangkatan }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 flex items-center justify-center mt-1">
                                    <i class="fas fa-clock text-xs mr-1"></i>
                                    @php
                                        $time =
                                            $booking->jadwal_jam ?? ($booking->jadwal ? $booking->jadwal->jam : null);
                                    @endphp
                                    @if (!empty($displayDate))
                                        {{ $displayDate->locale('id')->isoFormat('D/M/Y') }}
                                        &nbsp;•&nbsp;{{ \Carbon\Carbon::parse($time)->format('H:i') ?? '-' }} WIB
                                    @else
                                        {{ $time ? \Carbon\Carbon::parse($time)->format('H:i') : '-' }} WIB
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded flex items-center justify-center">
                                        <i class="fas fa-bus-alt text-indigo-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $booking->jadwal->mobil ? $booking->jadwal->mobil->merk : '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->jadwal->mobil ? $booking->jadwal->mobil->nomor_polisi : '-' }}
                                        </div>
                                    </div>
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
                            <td class="px-4 py-2 border border-white">

                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST"
                                    class="flex items-center justify-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" id="status_{{ $booking->id }}"
                                        value="">

                                    @if ($booking->status !== 'batal' && $booking->payment_status !== 'sudah_bayar')
                                        <button type="submit"
                                            onclick="document.getElementById('status_{{ $booking->id }}').value='setuju'"
                                            class="bg-green-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-green-700 transition inline-flex items-center gap-1.5 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Setuju
                                        </button>
                                        <button type="submit"
                                            onclick="document.getElementById('status_{{ $booking->id }}').value='batal'"
                                            class="bg-red-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-red-700 transition inline-flex items-center gap-1.5 shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                    clip-rule="evenodd" />
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


        <!-- Pagination (desktop) -->
        <div class="px-6 py-4 border-t border-gray-100 hidden lg:block">
            <div class="flex items-center justify-end">
                {{ $bookings->links('vendor.pagination.compact') }}
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4 fade-up animate-on-scroll">

            @foreach ($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">

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
                    </div>

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
                        @php
                            // Mobile: same logic as desktop to pick a reasonable display date
                            $mDisplayDate = null;
                            if (!empty($booking->tanggal)) {
                                try {
                                    $mDisplayDate = \Carbon\Carbon::parse($booking->tanggal);
                                } catch (\Exception $e) {
                                    $mDisplayDate = null;
                                }
                            }

                            if (!$mDisplayDate && $booking->jadwal) {
                                try {
                                    $mUpcoming = $booking->jadwal->getUpcomingDates(4);
                                    if ($mUpcoming && $mUpcoming->count() > 0) {
                                        $mDisplayDate = $mUpcoming->first();
                                    }
                                } catch (\Exception $e) {
                                    // ignore
                                }
                            }

                            $mTime = $booking->jadwal_jam ?? ($booking->jadwal ? $booking->jadwal->jam : null);
                        @endphp

                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium text-gray-900">
                                @if ($mDisplayDate)
                                    {{ $mDisplayDate->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                @else
                                    {{ $booking->jadwal_hari_keberangkatan }}
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Jam:</span>
                            <span
                                class="font-medium text-gray-900">{{ $mTime ? \Carbon\Carbon::parse($mTime)->format('H:i') : '-' }}
                                WIB</span>
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

                    <div class="pt-3 border-t border-gray-100">

                        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST"
                            class="flex items-center justify-center gap-2">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" id="status_mobile_{{ $booking->id }}" value="">

                            @if ($booking->status !== 'batal' && $booking->payment_status !== 'sudah_bayar')
                                <button type="submit"
                                    onclick="document.getElementById('status_mobile_{{ $booking->id }}').value='setuju'"
                                    class="flex-1 bg-green-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-green-700 transition inline-flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Setuju
                                </button>
                                <button type="submit"
                                    onclick="document.getElementById('status_mobile_{{ $booking->id }}').value='batal'"
                                    class="flex-1 bg-red-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-red-700 transition inline-flex items-center justify-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                            clip-rule="evenodd" />
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

        <!-- Pagination (mobile) -->
        <div class="mt-4 flex justify-center lg:hidden">
            {{ $bookings->links('vendor.pagination.compact') }}
        </div>
    @endsection
