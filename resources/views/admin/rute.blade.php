@extends('layouts.app')

@section('page-title', 'Rute Perjalanan')

@section('page-subtitle', 'Kelola rute dan harga tiket')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row gap-3">


            <form method="GET" action="{{ route('admin.rute') }}" class="flex-1 flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari rute..."
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
                    <a href="{{ route('admin.rute') }}"
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

            <a href="{{ route('admin.rute.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center gap-2 transition fade-left animate-on-scroll whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Rute
            </a>
        </div>
    </div>

    <div
        class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden fade-up animate-on-scroll">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rute
                            Perjalanan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jarak /
                            Estimasi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jam
                            Keberangkatan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga
                            Tiket</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rutes as $rute)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rute->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-route text-purple-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $rute->kota_asal }}</span>
                                            <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                            <span
                                                class="text-sm font-semibold text-gray-900">{{ $rute->kota_tujuan }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-road text-gray-400 mr-2"></i>
                                    {{ $rute->jarak_estimasi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg">
                                    <i class="fas fa-clock mr-2 text-xs"></i>
                                    <span class="text-sm font-semibold">{{ $rute->jam_keberangkatan ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format(preg_replace('/[^\d]/', '', $rute->harga_tiket), 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle text-xs mr-1"></i>
                                    {{ $rute->status_rute }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.rute.edit', $rute) }}"
                                        class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.rute.destroy', $rute) }}" method="POST"
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
                                    <i class="fas fa-route text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada rute</p>
                                    <p class="text-gray-400 text-sm mt-1">Tambahkan rute perjalanan baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
            @forelse($rutes as $rute)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg">{{ $rute->kota_asal }} →
                                {{ $rute->kota_tujuan }}
                            </h3>
                            <p class="text-xs text-gray-500">ID: {{ $rute->id }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            {{ $rute->status_rute }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-gray-700">Jarak: {{ $rute->jarak_estimasi }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            <span class="font-semibold text-blue-600">Rp
                                {{ number_format(preg_replace('/[^\d]/', '', $rute->harga_tiket), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.rute.edit', $rute) }}"
                            class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                            <i class="fas fa-pen-to-square"></i> Edit
                        </a>

                        <form action="{{ route('admin.rute.destroy', $rute) }}" method="POST"
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
                    Belum ada rute
                </div>
            @endforelse
        </div>

        <div class="mt-6 flex justify-end">
            {{ $rutes->links('pagination::tailwind') }}
        </div>
    @endsection
