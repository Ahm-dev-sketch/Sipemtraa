@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-2 mb-4 fade-right animate-on-scroll">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            Data Rute
        </h1>
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search Form --}}
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

                <!-- Tombol Reset -->
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

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white p-6 rounded shadow overflow-x-auto fade-up animate-on-scroll">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 border border-white">ID Rute</th>
                    <th class="px-4 py-3 border border-white">Kota Asal</th>
                    <th class="px-4 py-3 border border-white">Kota Tujuan</th>
                    <th class="px-4 py-3 border border-white">Jarak / Estimasi</th>
                    <th class="px-4 py-3 border border-white">Harga Tiket</th>
                    <th class="px-4 py-3 border border-white">Status</th>
                    <th class="px-4 py-3 border border-white text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($rutes as $rute)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border border-white">{{ $rute->id }}</td>
                        <td class="px-4 py-2 border border-white">{{ $rute->kota_asal }}</td>
                        <td class="px-4 py-2 border border-white">{{ $rute->kota_tujuan }}</td>
                        <td class="px-4 py-2 border border-white">{{ $rute->jarak_estimasi }}</td>
                        <td class="px-4 py-2 border border-white">Rp
                            {{ number_format(preg_replace('/[^\d]/', '', $rute->harga_tiket), 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border border-white">
                            <span
                                class="px-2 py-1 rounded text-sm bg-green-100 text-green-700">{{ $rute->status_rute }}</span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <div class="flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.rute.edit', $rute) }}"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.rute.destroy', $rute) }}" method="POST" class="delete-form">
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
                        <td colspan="7" class="text-center py-4 text-gray-500">Belum ada rute</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @forelse($rutes as $rute)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $rute->kota_asal }} → {{ $rute->kota_tujuan }}
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
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.rute.destroy', $rute) }}" method="POST" class="flex-1 delete-form">
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
                Belum ada rute
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-end">
        {{ $rutes->links() }}
    </div>
@endsection
