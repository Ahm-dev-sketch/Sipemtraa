@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2" data-aos="fade-down">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 19V6a2 2 0 012-2h2m4 0h2a2 2 0 012 2v13a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2h4" />
        </svg>
        Laporan Pendapatan
    </h1>

    <!-- Cards ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in">
            <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500">Total Pendapatan</h3>
                <div class="flex items-center gap-2">
                    <p id="total-revenue-amount" class="text-2xl font-bold text-green-700">Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    <button id="toggle-total-revenue-visibility" aria-label="Toggle total revenue visibility"
                        class="focus:outline-none">
                        <svg id="eye-icon-total" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M9 21V3m6 18V3" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500">Pendapatan Bulan Ini</h3>
                <div class="flex items-center gap-2">
                    <p id="monthly-revenue-amount" class="text-2xl font-bold text-blue-700">Rp
                        {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                    <button id="toggle-monthly-revenue-visibility" aria-label="Toggle monthly revenue visibility"
                        class="focus:outline-none">
                        <svg id="eye-icon-monthly" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-700"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow flex items-center gap-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 11V9a4 4 0 118 0v2a4 4 0 11-8 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-gray-500">Transaksi Selesai</h3>
                <p class="text-2xl font-bold text-yellow-700">{{ $transaksiSelesai }}</p>
            </div>
        </div>
    </div>

    <!-- Grafik pendapatan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Grafik Batang -->
        <div class="bg-white p-6 rounded shadow" data-aos="fade-up">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Grafik Batang Pendapatan (7 Hari Terakhir)
            </h3>
            <canvas id="chartPendapatanBar" height="100" data-labels="{{ json_encode($labels7Hari) }}"
                data-data="{{ json_encode($pendapatan7Hari) }}"></canvas>
        </div>

        <!-- Grafik Garis -->
        <div class="bg-white p-6 rounded shadow" data-aos="fade-up" data-aos-delay="200">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4" />
                </svg>
                Grafik Garis Pendapatan (7 Hari Terakhir)
            </h3>
            <canvas id="chartPendapatanLine" height="100" data-labels="{{ json_encode($labels7Hari) }}"
                data-data="{{ json_encode($pendapatan7Hari) }}"></canvas>
        </div>
    </div>

    <!-- Grafik Pie -->
    <div class="flex justify-center mb-6">
        <div class="bg-white p-6 rounded shadow w-full max-w-md" data-aos="fade-up" data-aos-delay="400">
            <h3 class="text-lg font-bold mb-4 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                Distribusi Pendapatan Bulan Ini
            </h3>
            <canvas id="chartPendapatanPie" height="200" data-labels="{{ json_encode($labelsBulanIni) }}"
                data-data="{{ json_encode($pendapatanBulanIniPerHari) }}"></canvas>
        </div>
    </div>
@endsection
