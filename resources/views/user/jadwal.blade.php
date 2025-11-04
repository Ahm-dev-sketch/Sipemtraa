@extends('layouts.app')

@section('content')
    <div class="mb-8 fade-down animate-on-scroll">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-1 h-8 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-600 rounded-full"></div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Jadwal <span
                    class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Keberangkatan</span>
            </h2>
        </div>
        <p class="text-gray-600 ml-7">Temukan jadwal perjalanan yang sesuai dengan kebutuhan Anda</p>
    </div>


    <form method="GET" action="{{ route('jadwal') }}" class="mb-8 fade-right animate-on-scroll">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Cari berdasarkan kota asal, tujuan, tanggal, atau waktu..."
                        class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-3.5 text-sm
                            focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                            transition-all duration-200 bg-gray-50 hover:bg-white">
                </div>
                <button type="submit"
                    class="group px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white
                        rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300
                        shadow-lg hover:shadow-xl hover:scale-105 font-semibold text-sm
                        flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fas fa-search"></i>
                    <span>Cari Jadwal</span>
                </button>
            </div>
        </div>
    </form>


    @if ($jadwals->isEmpty())
        <div class="fade-up animate-on-scroll">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-6">
                    <i class="fas fa-calendar-times text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Jadwal Ditemukan</h3>
                <p class="text-gray-600 mb-6">Maaf, jadwal yang Anda cari tidak tersedia saat ini.</p>
                <a href="{{ route('jadwal') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600
                        text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300
                        shadow-lg hover:shadow-xl hover:scale-105 font-semibold">
                    <i class="fas fa-refresh"></i>
                    <span>Lihat Semua Jadwal</span>
                </a>
            </div>
        </div>
    @else
        <div class="space-y-6 fade-up animate-on-scroll">

            @foreach ($jadwals as $jadwal)
                <div
                    class="group bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl
                    transition-all duration-300 overflow-hidden hover:border-blue-200 hover:-translate-y-1">
                    <div class="p-6 md:p-8">

                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></div>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Rute Perjalanan</h3>
                            </div>
                            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 mb-4">
                                <div class="flex-1">
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border-2 border-blue-200 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-600 font-medium mb-0.5">Keberangkatan Dari</p>
                                                <p class="text-xl font-bold text-gray-900">
                                                    {{ $jadwal->rute->kota_asal ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex md:flex-col items-center justify-center gap-2 px-4 py-2 md:py-0">
                                    <div class="flex md:flex-col items-center gap-2">
                                        <div
                                            class="w-12 md:w-0.5 h-0.5 md:h-6 bg-gradient-to-r md:bg-gradient-to-b from-blue-400 to-indigo-400">
                                        </div>
                                        <div
                                            class="shrink-0 w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                            <i class="fas fa-bus text-white text-sm"></i>
                                        </div>
                                        <div
                                            class="w-12 md:w-0.5 h-0.5 md:h-6 bg-gradient-to-r md:bg-gradient-to-b from-indigo-400 to-purple-400">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
                                        Langsung
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border-2 border-purple-200 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                                <i class="fas fa-flag-checkered text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-600 font-medium mb-0.5">Tujuan Ke</p>
                                                <p class="text-xl font-bold text-gray-900">
                                                    {{ $jadwal->rute->kota_tujuan ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-road text-blue-600"></i>
                                    <span>Tanpa Transit</span>
                                </div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-green-600"></i>
                                    <span>Aman & Nyaman</span>
                                </div>
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-indigo-600"></i>
                                    <span>Supir Profesional</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                            <div
                                class="flex items-center gap-4 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <div
                                    class="shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar-day text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-medium mb-0.5">Tanggal Berangkat</p>
                                    <p class="font-bold text-gray-900 text-sm">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-4 p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                                <div
                                    class="shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-medium mb-0.5">Waktu Keberangkatan</p>
                                    <p class="font-bold text-gray-900 text-sm">
                                        {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} WIB
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-4 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <div
                                    class="shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-chair text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 font-medium mb-0.5">Kapasitas</p>
                                    <p class="font-bold text-gray-900 text-sm">
                                        {{ $jadwal->mobil->kapasitas ?? '-' }} Kursi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col md:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tag text-gray-400"></i>
                                    <span class="text-sm text-gray-600">Harga per orang</span>
                                </div>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-3xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                        Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            @auth

                                @if (auth()->user()->role === 'user')
                                    <a href="{{ route('booking.quick', $jadwal->id) }}"
                                        class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600
                                            text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300
                                            shadow-lg hover:shadow-xl hover:scale-105 font-bold whitespace-nowrap">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Pesan Sekarang</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-gray-600 to-slate-600
                                            text-white rounded-xl hover:from-gray-700 hover:to-slate-700 transition-all duration-300
                                            shadow-lg hover:shadow-xl hover:scale-105 font-bold whitespace-nowrap">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Lihat Dashboard</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login', ['redirect' => route('booking.quick', $jadwal->id)]) }}"
                                    class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600
                                        text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300
                                        shadow-lg hover:shadow-xl hover:scale-105 font-bold whitespace-nowrap">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Login & Pesan</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="mt-8 flex justify-center fade-up animate-on-scroll">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-2">
                {{ $jadwals->links('pagination::tailwind') }}
            </div>
        </div>
    @endif

@endsection
