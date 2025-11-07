@extends('layouts.app')

@section('page-title', 'Data Supir')

@section('page-subtitle', 'Kelola informasi pengemudi')

@section('content')
    <!-- Header section: Bagian header dengan search dan tombol tambah supir -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row gap-3">


            <form method="GET" action="{{ route('admin.supir') }}" class="flex-1 flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari supir..."
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
                    <a href="{{ route('admin.supir') }}"
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


            <a href="{{ route('admin.supir.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center gap-2 transition fade-left animate-on-scroll whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Supir
            </a>
        </div>
    </div>

    <!-- Table view untuk desktop: Tabel daftar supir dengan kolom ID, supir, telepon, mobil, dan aksi -->
    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Supir
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Telepon
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mobil
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($supirs as $supir)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $supir->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-id-card text-teal-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $supir->nama }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">

                                @if ($supir->telepon)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-phone-alt text-gray-400 mr-2"></i>
                                        {{ $supir->telepon }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded flex items-center justify-center">
                                        <i class="fas fa-bus-alt text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $supir->mobil->merk ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $supir->mobil->nomor_polisi ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.supir.edit', $supir) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.supir.destroy', $supir) }}" method="POST"
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-id-card text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada data supir</p>
                                    <p class="text-gray-400 text-sm mt-1">Tambahkan supir baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card view untuk mobile: Tampilan kartu untuk setiap supir dengan informasi dan tombol aksi -->
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @forelse($supirs as $supir)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $supir->nama }}</h3>
                        <p class="text-xs text-gray-500">ID: {{ $supir->id }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        <span class="text-gray-700">{{ $supir->telepon ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17V7m0 10a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 110 4 2 2 0 01-4 0 2 2 0 014 0zm0 0V7a2 2 0 012-2h6a2 2 0 012 2v10M9 7a2 2 0 012-2h6a2 2 0 012 2m0 10a2 2 0 100 4 2 2 0 000-4zm0 0a2 2 0 110 4 2 2 0 01-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-gray-700">
                            {{ $supir->mobil->merk ?? 'N/A' }} ({{ $supir->mobil->nomor_polisi ?? 'N/A' }})
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.supir.edit', $supir) }}"
                        class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                        <i class="fas fa-pen-to-square"></i> Edit
                    </a>

                    <form action="{{ route('admin.supir.destroy', $supir) }}" method="POST" class="flex-1 delete-form">
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
                Belum ada data supir
            </div>
        @endforelse
    </div>

    <!-- Pagination section: Navigasi halaman untuk daftar supir -->
    <div class="mt-6 flex justify-center lg:justify-end">
        {{ $supirs->links('vendor.pagination.compact') }}
    </div>
@endsection
