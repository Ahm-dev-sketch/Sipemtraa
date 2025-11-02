@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-2 mb-4 fade-right animate-on-scroll">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 4h10M5 11h14v10H5V11z" />
            </svg>
            Kelola Jadwal
        </h1>
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.jadwals') }}" class="flex-1 flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari jadwal..."
                        class="px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Tombol Reset -->
                @if (isset($search) && $search)
                    <a href="{{ route('admin.jadwals') }}"
                        class="text-gray-600 hover:text-gray-800 flex items-center gap-1 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.jadwals.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center gap-2 transition fade-left animate-on-scroll whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white p-6 rounded shadow overflow-x-auto fade-up animate-on-scroll">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 border border-white">ID</th>
                    <th class="px-4 py-3 border border-white">Kota Awal</th>
                    <th class="px-4 py-3 border border-white">Kota Tujuan</th>
                    <th class="px-4 py-3 border border-white">Tanggal</th>
                    <th class="px-4 py-3 border border-white">Jam</th>
                    <th class="px-4 py-3 border border-white">Harga</th>
                    <th class="px-4 py-3 border border-white text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($jadwals as $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border border-white">{{ $jadwal->id }}</td>
                        <td class="px-4 py-2 border border-white">{{ $jadwal->rute ? $jadwal->rute->kota_asal : '-' }}</td>
                        <td class="px-4 py-2 border border-white">{{ $jadwal->rute ? $jadwal->rute->kota_tujuan : '-' }}
                        </td>
                        <td class="px-4 py-2 border border-white">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                        <td class="px-4 py-2 border border-white">{{ $jadwal->jam }} WIB</td>
                        <td class="px-4 py-2 border border-white">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border border-white">
                            <div class="flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.jadwals.edit', $jadwal) }}"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.jadwals.destroy', $jadwal) }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada jadwal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @forelse($jadwals as $jadwal)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">
                            {{ $jadwal->rute ? $jadwal->rute->kota_asal : '-' }} →
                            {{ $jadwal->rute ? $jadwal->rute->kota_tujuan : '-' }}</h3>
                        <p class="text-xs text-gray-500">ID: {{ $jadwal->id }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                        Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                    </span>
                </div>

                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-gray-700">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $jadwal->jam }} WIB</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.jadwals.edit', $jadwal) }}"
                        class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.jadwals.destroy', $jadwal) }}" method="POST"
                        class="flex-1 delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-600 text-white text-sm px-3 py-2 rounded hover:bg-red-700 transition inline-flex items-center justify-center gap-1">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                Belum ada jadwal
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-end w-full pr-4">
        {{ $jadwals->links() }}
    </div>
@endsection
