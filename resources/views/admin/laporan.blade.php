@extends('layouts.app')

@section('page-title', 'Laporan Pendapatan')
@section('page-subtitle', 'Monitor dan analisis pendapatan tiket')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Pendapatan Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                    </div>
                    <button id="toggle-total-revenue-visibility" aria-label="Toggle total revenue visibility"
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg id="eye-icon-total" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-400 hover:text-emerald-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Pendapatan</p>
                    <p id="total-revenue-amount" class="text-2xl font-bold text-slate-800 mb-1">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center gap-1 text-xs text-emerald-600">
                        <i class="fas fa-chart-line"></i>
                        <span>Keseluruhan transaksi</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
        </div>

        <!-- Pendapatan Bulan Ini Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                    </div>
                    <button id="toggle-monthly-revenue-visibility" aria-label="Toggle monthly revenue visibility"
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg id="eye-icon-monthly" xmlns="http://www.w3.org/2000/svg"
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
                    <p id="monthly-revenue-amount" class="text-2xl font-bold text-slate-800 mb-1">
                        Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center gap-1 text-xs text-blue-600">
                        <i class="fas fa-arrow-up"></i>
                        <span>Periode berjalan</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        </div>

        <!-- Transaksi Selesai Card -->
        <div
            class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-300 transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Transaksi Selesai</p>
                    <p class="text-2xl font-bold text-slate-800 mb-1">{{ $transaksiSelesai }}</p>
                    <div class="flex items-center gap-1 text-xs text-slate-500">
                        <i class="fas fa-check-double"></i>
                        <span>Booking terkonfirmasi</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
        </div>
    </div>

    <!-- Grafik pendapatan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Grafik Batang -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Grafik Batang Pendapatan</h3>
                        <p class="text-xs text-slate-500">7 Hari Terakhir</p>
                    </div>
                </div>
                <span
                    class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg">
                    <i class="fas fa-calendar-alt"></i>
                    Weekly
                </span>
            </div>
            <canvas id="chartPendapatanBar" height="100" data-labels="{{ json_encode($labels7Hari) }}"
                data-data="{{ json_encode($pendapatan7Hari) }}"></canvas>
        </div>

        <!-- Grafik Garis -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Grafik Garis Pendapatan</h3>
                        <p class="text-xs text-slate-500">7 Hari Terakhir</p>
                    </div>
                </div>
                <span
                    class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg">
                    <i class="fas fa-chart-line"></i>
                    Trend
                </span>
            </div>
            <canvas id="chartPendapatanLine" height="100" data-labels="{{ json_encode($labels7Hari) }}"
                data-data="{{ json_encode($pendapatan7Hari) }}"></canvas>
        </div>
    </div>

    <!-- Grafik Pie -->
    <div class="flex justify-center">
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 w-full max-w-md hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Distribusi Pendapatan</h3>
                        <p class="text-xs text-slate-500">Bulan Ini Per Hari</p>
                    </div>
                </div>
            </div>
            <div class="max-w-sm mx-auto">
                <canvas id="chartPendapatanPie" height="280" data-labels="{{ json_encode($labelsBulanIni) }}"
                    data-data="{{ json_encode($pendapatanBulanIniPerHari) }}"></canvas>
            </div>
        </div>
    </div>
@endsection
