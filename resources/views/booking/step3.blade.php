@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Progress Bar -->
            <div class="mb-8 fade-down animate-on-scroll">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                ✓
                            </div>
                            <span class="ml-2 text-sm font-medium text-green-600">Pilih Perjalanan</span>
                        </div>

                        <!-- Connector -->
                        <div class="w-16 h-1 bg-green-600"></div>

                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                ✓
                            </div>
                            <span class="ml-2 text-sm font-medium text-green-600">Pilih Rute</span>
                        </div>

                        <!-- Connector -->
                        <div class="w-16 h-1 bg-[#FF6B2C]"></div>

                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-[#FF6B2C] text-white rounded-full flex items-center justify-center text-sm font-bold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-[#FF6B2C]">Pilih Kursi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="bg-white rounded-lg shadow mb-6 p-6 fade-right animate-on-scroll">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pemesanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Rute</div>
                        <div class="font-medium">{{ $step1Data['kota_asal'] }} → {{ $step1Data['kota_tujuan'] }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Tanggal & Jam</div>
                        <div class="font-medium">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} - {{ $jadwal->jam }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Mobil</div>
                        <div class="font-medium">
                            {{ $jadwal->mobil->merk ?? 'N/A' }} ({{ $jadwal->mobil->jenis ?? 'N/A' }})
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Supir</div>
                        <div class="font-medium">
                            {{ $jadwal->mobil->supir->nama ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Harga per Kursi</div>
                        <div class="font-medium">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-lg p-8 fade-up animate-on-scroll">
                <div class="text-center mb-8 zoom-in animate-on-scroll">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pilih Kursi Anda</h1>
                    <p class="text-gray-600">Pilih kursi yang ingin dipesan (maksimal 7 kursi)</p>
                </div>

                <!-- Seat Layout -->
                <div class="mb-8 fade-up animate-on-scroll">
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Layout Kursi Mobil</h3>
                        <div class="flex justify-center items-center space-x-6 text-sm text-gray-600">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-400 border rounded mr-2 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span>Supir</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-200 border rounded mr-2"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 border rounded mr-2"></div>
                                <span>Terpilih</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 border rounded mr-2"></div>
                                <span>Sudah Dipesan</span>
                            </div>
                        </div>
                    </div>

                    <form id="seat-form" action="{{ route('booking.step3.process') }}" method="POST"
                        data-price-per-seat="{{ $jadwal->harga }}">
                        @csrf

                        <!-- Seat Grid -->
                        <div class="flex justify-center mb-6">
                            <div class="space-y-4">
                                <!-- Front Row: Seat A1 (left) + Driver (right) -->
                                <div class="flex justify-center space-x-4">
                                    <!-- Seat A1 (left side) -->
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="seats[]" value="A1" class="seat-checkbox hidden"
                                            id="seat-A1" {{ in_array('A1', $bookedSeats) ? 'disabled' : '' }}>
                                        <div for="seat-A1"
                                            class="w-16 h-16 border-2 rounded-lg flex items-center justify-center text-sm font-semibold transition-all seat-box
                                                @if (in_array('A1', $bookedSeats)) bg-red-500 text-white border-red-500 cursor-not-allowed
                                                @else
                                                    bg-gray-200 text-gray-700 border-gray-300 hover:border-blue-400 @endif">
                                            A1
                                        </div>
                                    </label>
                                    <!-- Driver Seat (right side) -->
                                    <div
                                        class="w-16 h-16 bg-gray-400 border-2 border-gray-500 rounded-lg flex flex-col items-center justify-center text-white cursor-not-allowed">
                                        <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-semibold">Supir</span>
                                    </div>
                                </div>

                                <!-- Back Rows: 3 rows of 4 seats each (2 behind A1 + 2 behind driver) -->
                                @for ($row = 0; $row < 3; $row++)
                                    <div class="flex justify-center space-x-4">
                                        @for ($col = 0; $col < 4; $col++)
                                            @php
                                                $seatNumber = 'A' . ($row * 4 + $col + 2); // A2, A3, A4, A5, A6, A7, A8, A9, A10, A11, A12, A13
                                            @endphp
                                            <label class="cursor-pointer">
                                                <input type="checkbox" name="seats[]" value="{{ $seatNumber }}"
                                                    class="seat-checkbox hidden" id="seat-{{ $seatNumber }}"
                                                    {{ in_array($seatNumber, $bookedSeats) ? 'disabled' : '' }}>
                                                <div for="seat-{{ $seatNumber }}"
                                                    class="w-16 h-16 border-2 rounded-lg flex items-center justify-center text-sm font-semibold transition-all seat-box
                                                        @if (in_array($seatNumber, $bookedSeats)) bg-red-500 text-white border-red-500 cursor-not-allowed
                                                        @else
                                                            bg-gray-200 text-gray-700 border-gray-300 hover:border-blue-400 @endif">
                                                    {{ $seatNumber }}
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Selected Seats Display -->
                        <div class="text-center mb-6">
                            <div class="text-sm text-gray-600 mb-2">Kursi Terpilih:</div>
                            <div id="selected-seats" class="font-medium text-blue-600 min-h-[24px]">
                                Belum ada kursi dipilih
                            </div>
                        </div>

                        <!-- Total Price -->
                        <div class="text-center mb-8">
                            <div class="text-lg font-semibold text-gray-900">
                                Total: <span id="total-price">Rp 0</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('booking.step2') }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Kembali
                            </a>

                            <button type="submit" id="book-button" disabled
                                class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                                Pesan Tiket
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
