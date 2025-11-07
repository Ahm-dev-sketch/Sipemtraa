@extends('layouts.app')

@section('page-title', 'Penjadwalan')

@section('page-subtitle', 'Kelola jadwal perjalanan dan ketersediaan')

@section('content')

    <!-- PESAN SUKSES - Menampilkan notifikasi sukses dari session -->
    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    <!-- HEADER DAN PENCARIAN - Bagian header dengan form pencarian dan tombol tambah -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row gap-3">

            <!-- FORM PENCARIAN - Form untuk mencari jadwal berdasarkan kata kunci -->
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

                <!-- TOMBOL RESET PENCARIAN - Muncul jika ada pencarian aktif -->
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

            <!-- TOMBOL TAMBAH JADWAL - Link ke halaman create jadwal baru -->
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

    <!-- TABEL JADWAL DESKTOP - Tabel untuk menampilkan daftar jadwal pada layar besar -->
    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rute
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Hari
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Waktu
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-route text-indigo-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $jadwal->rute ? $jadwal->rute->kota_asal : '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            {{ $jadwal->rute ? $jadwal->rute->kota_tujuan : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-calendar-week text-gray-400 mr-2"></i>
                                    Setiap {{ $jadwal->hari_keberangkatan }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-clock text-xs mr-1"></i> {{ $jadwal->jam }} WIB
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format($jadwal->harga, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">

                                    <form action="{{ route('admin.jadwals.toggle', $jadwal) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 {{ $jadwal->is_active ? 'bg-green-50 hover:bg-green-100 text-green-600' : 'bg-gray-50 hover:bg-gray-100 text-gray-600' }} rounded-lg transition-colors duration-150"
                                            title="{{ $jadwal->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i
                                                class="fas fa-{{ $jadwal->is_active ? 'toggle-on' : 'toggle-off' }} text-sm"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.jadwals.edit', $jadwal) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.jadwals.destroy', $jadwal) }}" method="POST"
                                        class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-150"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-alt text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada jadwal</p>
                                    <p class="text-gray-400 text-sm mt-1">Tambahkan jadwal baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TAMPILAN MOBILE - Tampilan kartu untuk perangkat mobile -->
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
                                Setiap {{ $jadwal->hari_keberangkatan }}
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

                        <form action="{{ route('admin.jadwals.toggle', $jadwal) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full {{ $jadwal->is_active ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white text-sm px-3 py-2 rounded transition inline-flex items-center justify-center gap-1">
                                <i class="fas fa-{{ $jadwal->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                {{ $jadwal->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.jadwals.edit', $jadwal) }}"
                            class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                            <i class="fas fa-pen-to-square"></i> Edit
                        </a>

                        <form action="{{ route('admin.jadwals.destroy', $jadwal) }}" method="POST"
                            class="flex-1 delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 text-white text-sm px-3 py-2 rounded hover:bg-red-700 transition inline-flex items-center justify-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus
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

        <!-- PAGINATION - Navigasi halaman untuk tabel jadwal -->
        <div class="mt-4 flex justify-center lg:justify-end w-full pr-4">
            {{ $jadwals->links('vendor.pagination.compact') }}
        </div>
    @endsection
