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
                        <div class="absolute top-6 left-0 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-500 -z-10"
                            style="width: 0%"></div>

                        <!-- Step 1 - Active -->
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg mb-2 ring-4 ring-blue-100">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <span class="text-sm font-semibold text-blue-600">Pilih Perjalanan</span>
                            <span class="text-xs text-gray-500 mt-1">Step 1 dari 3</span>
                        </div>

                        <!-- Step 2 - Inactive -->
                        <div class="flex flex-col items-center flex-1 relative">
                            <div
                                class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold shadow mb-2">
                                <i class="fas fa-route"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Pilih Jadwal</span>
                            <span class="text-xs text-gray-400 mt-1">Step 2 dari 3</span>
                        </div>

                        <!-- Step 3 - Inactive -->
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

            <!-- Main Content -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8 fade-up animate-on-scroll">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-4">
                        <i class="fas fa-map-marked-alt text-3xl text-blue-600"></i>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Pilih <span
                            class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Perjalanan</span>
                    </h1>
                    <p class="text-gray-600">Tentukan kota asal, tujuan, dan tanggal keberangkatan Anda</p>
                </div>

                <form action="{{ route('booking.step1') }}" method="POST" class="max-w-2xl mx-auto">
                    @csrf

                    <!-- Journey Selection -->
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></div>
                            <h3 class="font-bold text-gray-900">Rute Perjalanan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kota Awal -->
                            <div class="relative">
                                <label for="kota_awal" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i>
                                    Dari Kota
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-city text-gray-400"></i>
                                    </div>
                                    <select id="kota_awal" name="kota_awal" required
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-gray-50 hover:bg-white appearance-none cursor-pointer font-medium">
                                        <option value="">Pilih kota keberangkatan</option>
                                        @foreach ($kotaAwal as $kota)
                                            <option value="{{ $kota }}"
                                                {{ old('kota_awal') == $kota ? 'selected' : '' }}>
                                                {{ $kota }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                @error('kota_awal')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kota Tujuan -->
                            <div class="relative">
                                <label for="kota_tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-flag-checkered text-purple-600 mr-1"></i>
                                    Ke Kota
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-city text-gray-400"></i>
                                    </div>
                                    <select id="kota_tujuan" name="kota_tujuan" required
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 bg-gray-50 hover:bg-white appearance-none cursor-pointer font-medium">
                                        <option value="">Pilih kota tujuan</option>
                                        @foreach ($kotaTujuan as $kota)
                                            <option value="{{ $kota }}"
                                                {{ old('kota_tujuan') == $kota ? 'selected' : '' }}>
                                                {{ $kota }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                @error('kota_tujuan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-6 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-full"></div>
                            <h3 class="font-bold text-gray-900">Tanggal Keberangkatan</h3>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}" required
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 bg-gray-50 hover:bg-white font-medium cursor-pointer">
                            @error('tanggal')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <p class="mt-2 text-sm text-gray-600 flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Pilih tanggal mulai dari hari ini
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('jadwal') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 font-semibold">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Jadwal</span>
                        </a>
                        <button type="submit"
                            class="flex-1 group inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 font-bold">
                            <span>Cari Perjalanan</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
