@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Modern Progress Indicator -->
            <div class="mb-8 fade-down animate-on-scroll">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between relative">
                        <!-- Progress Line Background -->
                        <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 -z-10"></div>
                        <!-- Active Progress Line -->
                        <div class="absolute top-6 left-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 transition-all duration-500 -z-10"
                            style="width: 100%"></div>

                        <!-- Step 1 - Completed -->
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-green-100">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-sm font-semibold text-green-600">Pilih Perjalanan</span>
                            <span class="text-xs text-gray-500 mt-1">Selesai</span>
                        </div>

                        <!-- Step 2 - Completed -->
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-green-100">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-sm font-semibold text-green-600">Pilih Jadwal</span>
                            <span class="text-xs text-gray-500 mt-1">Selesai</span>
                        </div>

                        <!-- Step 3 - Active -->
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-purple-100">
                                <i class="fas fa-chair"></i>
                            </div>
                            <span class="text-sm font-semibold text-purple-600">Pilih Kursi</span>
                            <span class="text-xs text-gray-500 mt-1">Step 3 dari 3</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div
                class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl shadow-lg border border-purple-100 mb-6 p-6 fade-right animate-on-scroll">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Detail Pemesanan</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-route text-blue-600"></i>
                            <span class="text-xs text-gray-600 font-medium">Rute Perjalanan</span>
                        </div>
                        <div class="font-bold text-gray-900">
                            <span class="text-blue-600">{{ $step1Data['kota_asal'] }}</span>
                            <i class="fas fa-arrow-right mx-1 text-indigo-600"></i>
                            <span class="text-purple-600">{{ $step1Data['kota_tujuan'] }}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-calendar-clock text-indigo-600"></i>
                            <span class="text-xs text-gray-600 font-medium">Tanggal & Jam</span>
                        </div>
                        <div class="font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} - {{ $jadwal->jam }}
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-car text-purple-600"></i>
                            <span class="text-xs text-gray-600 font-medium">Kendaraan</span>
                        </div>
                        <div class="font-bold text-gray-900">
                            {{ $jadwal->mobil->merk ?? 'N/A' }} ({{ $jadwal->mobil->jenis ?? 'N/A' }})
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-user-tie text-pink-600"></i>
                            <span class="text-xs text-gray-600 font-medium">Supir</span>
                        </div>
                        <div class="font-bold text-gray-900">
                            {{ $jadwal->mobil->supir->nama ?? 'N/A' }}
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-3 shadow-sm border border-green-200 md:col-span-2">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-tag text-green-600"></i>
                            <span class="text-xs text-gray-600 font-medium">Harga per Kursi</span>
                        </div>
                        <div
                            class="font-bold text-xl bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 fade-up animate-on-scroll">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full mb-4">
                        <i class="fas fa-chair text-3xl text-purple-600"></i>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Pilih <span
                            class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 bg-clip-text text-transparent">Kursi</span>
                    </h1>
                    <p class="text-gray-600">Pilih kursi yang ingin dipesan (maksimal 7 kursi per transaksi)</p>
                </div>

                <!-- Seat Layout -->
                <div class="mb-8 fade-up animate-on-scroll">
                    <!-- Legend -->
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-4 mb-6 border border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            <h3 class="font-bold text-gray-900">Keterangan Kursi</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-lg flex items-center justify-center shadow-md">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Supir</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 text-white rounded-lg flex items-center justify-center shadow-md">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 text-white rounded-lg flex items-center justify-center shadow-md ring-2 ring-green-300">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Terpilih</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center shadow-md">
                                    <i class="fas fa-times"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Terpesan</span>
                            </div>
                        </div>
                    </div>

                    <form id="seat-form" action="{{ route('booking.step3.process') }}" method="POST"
                        data-price-per-seat="{{ $jadwal->harga }}">
                        @csrf

                        <!-- Layout Interior Mobil Hiace -->
                        <div class="flex justify-center mb-6">
                            <div class="relative bg-gradient-to-b from-gray-700 to-gray-800 rounded-3xl p-12 shadow-2xl border-4 border-gray-900"
                                style="width: 380px;">
                                <!-- Dashboard/Depan Mobil -->
                                <div
                                    class="absolute top-0 left-0 right-0 h-8 bg-gray-900 rounded-t-3xl border-b-2 border-gray-600">
                                </div>

                                <!-- Windshield (Kaca Depan) -->
                                <div
                                    class="absolute top-8 left-8 right-8 h-2 bg-gradient-to-r from-transparent via-blue-300 to-transparent opacity-40 rounded">
                                </div>

                                <!-- Grid Layout Kursi -->
                                <div class="inline-grid gap-4 mt-6" style="grid-template-columns: 64px 64px 64px 64px;">

                                    <!-- Baris 1: Kursi 1 | KOSONG | KOSONG | Supir -->
                                    <label class="cursor-pointer transform transition-all hover:scale-105">
                                        <input type="checkbox" name="seats[]" value="1" id="seat-1"
                                            class="hidden seat-checkbox"
                                            {{ in_array('1', $bookedSeats) ? 'disabled' : '' }}>
                                        <div
                                            class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array('1', $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                            <span class="text-lg">1</span>
                                        </div>
                                    </label>
                                    <div class="relative">
                                        <!-- Lorong/Aisle Indicator -->
                                        <div class="w-16 h-16 flex items-center justify-center">
                                            <div class="w-1 h-12 bg-gray-600 rounded-full opacity-30"></div>
                                        </div>
                                    </div>
                                    <div></div>
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-gray-600 to-gray-700 text-white flex flex-col items-center justify-center rounded-xl border-3 border-gray-800 shadow-xl">
                                        <svg class="w-7 h-7 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-bold">SUPIR</span>
                                    </div>

                                    <!-- Baris 2: KOSONG | 2 | 3 | 4 -->
                                    <div class="relative">
                                        <div class="w-16 h-16 flex items-center justify-center">
                                            <div class="w-1 h-12 bg-gray-600 rounded-full opacity-30"></div>
                                        </div>
                                    </div>
                                    @foreach ([2, 3, 4] as $seat)
                                        <label class="cursor-pointer transform transition-all hover:scale-105">
                                            <input type="checkbox" name="seats[]" value="{{ $seat }}"
                                                id="seat-{{ $seat }}" class="hidden seat-checkbox"
                                                {{ in_array($seat, $bookedSeats) ? 'disabled' : '' }}>
                                            <div
                                                class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array($seat, $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                                <span class="text-lg">{{ $seat }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    <!-- Baris 3: 5 | LORONG | 6 | 7 -->
                                    <label class="cursor-pointer transform transition-all hover:scale-105">
                                        <input type="checkbox" name="seats[]" value="5" id="seat-5"
                                            class="hidden seat-checkbox"
                                            {{ in_array('5', $bookedSeats) ? 'disabled' : '' }}>
                                        <div
                                            class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array('5', $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                            <span class="text-lg">5</span>
                                        </div>
                                    </label>
                                    <div class="relative">
                                        <div class="w-16 h-16 flex items-center justify-center">
                                            <div class="w-1 h-12 bg-gray-600 rounded-full opacity-30"></div>
                                        </div>
                                    </div>
                                    @foreach ([6, 7] as $seat)
                                        <label class="cursor-pointer transform transition-all hover:scale-105">
                                            <input type="checkbox" name="seats[]" value="{{ $seat }}"
                                                id="seat-{{ $seat }}" class="hidden seat-checkbox"
                                                {{ in_array($seat, $bookedSeats) ? 'disabled' : '' }}>
                                            <div
                                                class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array($seat, $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                                <span class="text-lg">{{ $seat }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    <!-- Baris 4: 8 | LORONG | 9 | 10 -->
                                    <label class="cursor-pointer transform transition-all hover:scale-105">
                                        <input type="checkbox" name="seats[]" value="8" id="seat-8"
                                            class="hidden seat-checkbox"
                                            {{ in_array('8', $bookedSeats) ? 'disabled' : '' }}>
                                        <div
                                            class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array('8', $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                            <span class="text-lg">8</span>
                                        </div>
                                    </label>
                                    <div class="relative">
                                        <div class="w-16 h-16 flex items-center justify-center">
                                            <div class="w-1 h-12 bg-gray-600 rounded-full opacity-30"></div>
                                        </div>
                                    </div>
                                    @foreach ([9, 10] as $seat)
                                        <label class="cursor-pointer transform transition-all hover:scale-105">
                                            <input type="checkbox" name="seats[]" value="{{ $seat }}"
                                                id="seat-{{ $seat }}" class="hidden seat-checkbox"
                                                {{ in_array($seat, $bookedSeats) ? 'disabled' : '' }}>
                                            <div
                                                class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array($seat, $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                                <span class="text-lg">{{ $seat }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    <!-- Baris 5: 11 | 12 | 13 | 14 -->
                                    @foreach ([11, 12, 13, 14] as $seat)
                                        <label class="cursor-pointer transform transition-all hover:scale-105">
                                            <input type="checkbox" name="seats[]" value="{{ $seat }}"
                                                id="seat-{{ $seat }}" class="hidden seat-checkbox"
                                                {{ in_array($seat, $bookedSeats) ? 'disabled' : '' }}>
                                            <div
                                                class="w-16 h-16 flex items-center justify-center rounded-xl border-3 font-bold shadow-lg transition-all
        @if (in_array($seat, $bookedSeats)) bg-red-600 text-white border-red-700 cursor-not-allowed shadow-red-500/50
        @else
            bg-gradient-to-br from-blue-400 to-blue-500 text-white border-blue-600 hover:from-blue-500 hover:to-blue-600 hover:shadow-xl hover:shadow-blue-500/50 @endif">
                                                <span class="text-lg">{{ $seat }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                </div>

                                <!-- Rear Bumper -->
                                <div
                                    class="absolute bottom-0 left-0 right-0 h-6 bg-gray-900 rounded-b-3xl border-t-2 border-gray-600">
                                </div>

                                <!-- Side Mirrors Effect -->
                                <div class="absolute -left-2 top-10 w-4 h-6 bg-gray-900 rounded-l-lg"></div>
                                <div class="absolute -right-2 top-10 w-4 h-6 bg-gray-900 rounded-r-lg"></div>
                            </div>
                        </div>

                        <!-- Selected Seats Display -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                                        <i class="fas fa-chair text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-600 mb-1">Kursi Terpilih</div>
                                        <div id="selected-seats" class="font-bold text-gray-900 min-h-6">
                                            Belum ada kursi dipilih
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-600 mb-1">Total Pembayaran</div>
                                    <div id="total-price"
                                        class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('booking.step2') }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 font-semibold">
                                <i class="fas fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                            <button type="submit" id="book-button" disabled
                                class="flex-1 group inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:scale-105 font-bold disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed disabled:shadow-none disabled:scale-100">
                                <span>Pesan Tiket Sekarang</span>
                                <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
