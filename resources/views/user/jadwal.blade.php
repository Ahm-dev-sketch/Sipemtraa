@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4 fade-down animate-on-scroll">
        Jadwal Keberangkatan
    </h2>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('jadwal') }}"
        class="mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 fade-right animate-on-scroll">

        <!-- Input dengan Icon -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </span>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari tujuan / tanggal / jam..."
                class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
        </div>

        <!-- Tombol Cari -->
        <button type="submit"
            class="flex items-center justify-center gap-1 px-4 py-2 bg-blue-600 text-white
                   rounded-lg hover:bg-blue-700 transition text-sm shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
            Cari
        </button>
    </form>

    @if ($jadwals->isEmpty())
        <p class="text-gray-500 fade-up animate-on-scroll">Tidak ada jadwal ditemukan.</p>
    @else
        {{-- Card View for All Devices --}}
        <div class="space-y-4 fade-up animate-on-scroll">
            @foreach ($jadwals as $jadwal)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
                    {{-- Route Info --}}
                    <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-200">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900 text-base">
                                {{ $jadwal->rute->kota_asal ?? '-' }}
                                <svg class="inline w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                {{ $jadwal->rute->kota_tujuan ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Schedule Details --}}
                    <div class="space-y-2">
                        {{-- Date --}}
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                            </span>
                        </div>

                        {{-- Time --}}
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Jam Berangkat:</span>
                            <span class="font-medium text-gray-900">{{ $jadwal->jam }} WIB</span>
                        </div>

                        {{-- Price --}}
                        <div class="flex items-center justify-between pt-2 mt-2 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span class="text-gray-600 text-sm">Harga:</span>
                            </div>
                            <span class="font-bold text-blue-600 text-lg">
                                Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-end w-full pr-4">
            {{ $jadwals->links() }}
        </div>
    @endif
@endsection
