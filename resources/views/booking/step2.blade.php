@extends('layouts.app')

@section('content')
    <!-- Container utama halaman booking step 2 -->
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Progress indicator step booking -->
            <div class="mb-8 fade-down animate-on-scroll">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between relative">
                        <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 -z-10"></div>
                        <div class="absolute top-6 left-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-600 transition-all duration-500 -z-10"
                            style="width: 50%"></div>
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-green-100">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-sm font-semibold text-green-600">Pilih Perjalanan</span>
                            <span class="text-xs text-gray-500 mt-1">Selesai</span>
                        </div>
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-indigo-100">
                                <i class="fas fa-route"></i>
                            </div>
                            <span class="text-sm font-semibold text-indigo-600">Pilih Jadwal</span>
                            <span class="text-xs text-gray-500 mt-1">Step 2 dari 3</span>
                        </div>
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold shadow mb-2">
                                <i class="fas fa-chair"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Pilih Kursi</span>
                            <span class="text-xs text-gray-400 mt-1">Step 3 dari 3</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Summary perjalanan yang dipilih -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow border border-blue-100 mb-6 p-6 fade-right animate-on-scroll">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                                <i class="fas fa-route text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Perjalanan Anda</div>
                                <div class="font-bold text-gray-900">
                                    <span class="text-blue-600">{{ $step1Data['kota_asal'] }}</span>
                                    <i class="fas fa-arrow-right mx-2 text-indigo-600"></i>
                                    <span class="text-purple-600">{{ $step1Data['kota_tujuan'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block w-px h-10 bg-gradient-to-b from-blue-200 to-indigo-200"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                                <i class="fas fa-calendar-alt text-indigo-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Tanggal Keberangkatan</div>
                                <div class="font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($step1Data['tanggal'])->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('pesan') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-indigo-600 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg font-semibold text-sm">
                        <i class="fas fa-edit"></i>
                        Ubah Perjalanan
                    </a>
                </div>
            </div>
            <!-- Container daftar jadwal tersedia -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 fade-up animate-on-scroll">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full mb-4">
                        <i class="fas fa-route text-3xl text-indigo-600"></i>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Pilih <span
                            class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">Jadwal</span>
                    </h1>
                    <p class="text-gray-600">{{ $jadwals->count() }} jadwal tersedia untuk perjalanan Anda</p>
                </div>

                @if ($jadwals->isEmpty())
                    <!-- Empty state ketika tidak ada jadwal -->
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Jadwal Tersedia</h3>
                        <p class="text-gray-600 mb-6">Belum ada jadwal untuk rute dan tanggal yang dipilih.</p>
                        <a href="{{ route('pesan') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl font-bold">
                            <i class="fas fa-search"></i>
                            Cari Perjalanan Lain
                        </a>
                    </div>
                @else
                    <!-- Grid container untuk daftar jadwal -->
                    <div class="grid gap-6">

                        @foreach ($jadwals as $jadwal)
                            <!-- Card jadwal individual -->
                            <div
                                class="group border-2 border-gray-100 rounded-2xl p-6 hover:border-indigo-300 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                                <div class="flex flex-col lg:flex-row gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-bus text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-lg text-gray-900">
                                                    {{ $jadwal->rute->kota_asal }} <i
                                                        class="fas fa-arrow-right text-indigo-600 mx-1"></i>
                                                    {{ $jadwal->rute->kota_tujuan }}
                                                </div>
                                                <div class="text-sm text-gray-600 flex items-center gap-2">
                                                    <i class="fas fa-info-circle text-blue-500"></i>
                                                    <span>Travel Langsung - Tanpa Transit</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-calendar text-blue-600"></i>
                                                    <span class="text-xs text-gray-600 font-medium">Tanggal</span>
                                                </div>
                                                <div class="font-bold text-gray-900">
                                                    <!-- Format tanggal dengan Carbon: Menampilkan tanggal dalam format hari bulan tahun -->
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                                </div>
                                            </div>
                                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-clock text-indigo-600"></i>
                                                    <span class="text-xs text-gray-600 font-medium">Jam</span>
                                                </div>
                                                <div class="font-bold text-gray-900">
                                                    {{ $jadwal->jam }}
                                                </div>
                                            </div>
                                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-car text-purple-600"></i>
                                                    <span class="text-xs text-gray-600 font-medium">Kendaraan</span>
                                                </div>
                                                <div class="font-bold text-gray-900">
                                                    {{ $jadwal->mobil->merk ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-3 shadow-sm border border-green-200">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-tag text-green-600"></i>
                                                    <span class="text-xs text-gray-600 font-medium">Harga</span>
                                                </div>
                                                <div
                                                    class="font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                                    <!-- Format harga dengan number_format: Menampilkan harga dalam format Rupiah -->
                                                    Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-snowflake"></i> AC
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-music"></i> Audio
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-couch"></i> Kursi Nyaman
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                                <i class="fas fa-luggage-cart"></i> Bagasi Luas
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center lg:justify-end">

                                        <form action="{{ route('booking.step2.process') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                            <button type="submit"
                                                class="group-hover:scale-105 inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-2xl font-bold">
                                                <span>Pilih Jadwal</span>
                                                <i
                                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
