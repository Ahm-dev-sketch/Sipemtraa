@extends('layouts.app')

@section('page-title', 'Armada Mobil')

@section('page-subtitle', 'Kelola data kendaraan dan kapasitas')

@section('content')
    <!-- Header section dengan search dan tombol tambah mobil -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row gap-3">


            <form method="GET" action="{{ route('admin.mobil') }}" class="flex-1 flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari mobil..."
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


                @if (isset($search) && $search)
                    <a href="{{ route('admin.mobil') }}"
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


            <a href="{{ route('admin.mobil.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center gap-2 transition fade-left animate-on-scroll whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Mobil
            </a>
        </div>
    </div>

    <!-- Table view untuk desktop: Tabel daftar mobil dengan kolom ID, nomor plat, jenis, kapasitas, tahun/merk, status, dan aksi -->
    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nomor
                            Plat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Kapasitas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tahun/Merk</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($mobils as $mobil)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mobil->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-bus-alt text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $mobil->nomor_polisi }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mobil->jenis }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-users text-xs mr-1"></i> {{ $mobil->kapasitas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mobil->tahun }} /
                                {{ $mobil->merk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    {{ $mobil->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.mobil.edit', $mobil) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.mobil.destroy', $mobil) }}" method="POST"
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-bus-alt text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada data mobil</p>
                                    <p class="text-gray-400 text-sm mt-1">Tambahkan mobil baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card view untuk mobile: Tampilan kartu untuk setiap mobil dengan informasi dan tombol aksi -->
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @forelse($mobils as $mobil)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $mobil->nomor_polisi }}</h3>
                        <p class="text-xs text-gray-500">{{ $mobil->merk }} - {{ $mobil->jenis }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        {{ $mobil->status }}
                    </span>
                </div>
                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-gray-700">Kapasitas: {{ $mobil->kapasitas }} orang</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-gray-700">Tahun: {{ $mobil->tahun }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.mobil.edit', $mobil) }}"
                        class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                        <i class="fas fa-pen-to-square"></i> Edit
                    </a>

                    <form action="{{ route('admin.mobil.destroy', $mobil) }}" method="POST" class="flex-1 delete-form">
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
                Belum ada data mobil
            </div>
        @endforelse
    </div>

    <!-- Pagination section: Navigasi halaman untuk daftar mobil -->
    <div class="mt-6 flex justify-center lg:justify-end">
        {{ $mobils->links('vendor.pagination.compact') }}
    </div>
@endsection
