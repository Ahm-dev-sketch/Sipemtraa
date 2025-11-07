@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('page-subtitle', 'Ringkasan data dan statistik sistem')

@section('content')
    <!-- Stats Cards Grid: Grid kartu statistik dashboard admin -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8 fade-up animate-on-scroll">
        <!-- Revenue Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                    </div>
                    <button id="toggle-revenue-visibility" aria-label="Toggle revenue visibility"
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400 hover:text-blue-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pendapatan Bulan Ini</p>
                    <p id="revenue-amount" class="text-2xl font-bold text-slate-800 mb-1">
                        <!-- Format pendapatan menggunakan number_format: Menampilkan pendapatan bulan ini dengan format Rupiah -->
                        Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center gap-1 text-xs text-green-600">
                        <i class="fas fa-arrow-up"></i>
                        <span>Dari booking yang sudah bayar</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        </div>
        <!-- Bookings Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-purple-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-ticket-alt text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pemesanan Bulan Ini</p>
                    <p class="text-2xl font-bold text-slate-800 mb-1">{{ $jumlahPemesananBulanIni }}</p>
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Total tiket terjual</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-purple-500 to-purple-600"></div>
        </div>
        <!-- Active Trips Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-green-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Perjalanan Aktif</p>
                    <p class="text-2xl font-bold text-slate-800 mb-1">{{ $perjalananAktif }}</p>
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <i class="fas fa-bus"></i>
                        <span>Jadwal tersedia</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-green-500 to-green-600"></div>
        </div>
        <!-- Total Customers Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-indigo-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-slate-800 mb-1">{{ $totalPelanggan }}</p>
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <i class="fas fa-user-check"></i>
                        <span>Pengguna terdaftar</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>
        </div>
    </div>
    <!-- Revenue Chart Section: Bagian grafik pendapatan 7 hari terakhir -->
    <div
        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 lg:p-8 hover:shadow-lg transition-all duration-300 fade-up animate-on-scroll">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Grafik Pendapatan</h3>
                    <p class="text-xs sm:text-sm text-slate-500">7 Hari Terakhir</p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 rounded-lg flex-shrink-0">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-emerald-700">Live Data</span>
            </div>
        </div>
        <div class="relative overflow-x-auto">
            <div class="min-w-[300px]">
                <canvas id="chartPendapatanLineDashboard" height="100" data-labels="{{ json_encode($labels7Hari) }}"
                    data-data="{{ json_encode($pendapatan7Hari) }}" data-dashboard="true"></canvas>
            </div>
        </div>
    </div>
@endsection
