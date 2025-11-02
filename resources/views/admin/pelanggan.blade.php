@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-2 mb-4 fade-right animate-on-scroll">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a7 7 0 00-14 0v2h5" />
            </svg>
            Data Pelanggan
        </h1>
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.pelanggan') }}" class="flex-1 flex items-center gap-2">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden shadow-sm flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari pelanggan..."
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
                    <a href="{{ route('admin.pelanggan') }}"
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
            <a href="{{ route('admin.pelanggan.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center gap-2 transition fade-left animate-on-scroll whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pelanggan
            </a>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white p-6 rounded shadow overflow-x-auto fade-up animate-on-scroll">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 border border-white">ID</th>
                    <th class="px-4 py-3 border border-white">Nama</th>
                    <th class="px-4 py-3 border border-white">WhatsApp</th>
                    <th class="px-4 py-3 border border-white">Role</th>
                    <th class="px-4 py-3 border border-white text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border border-white">{{ $customer->id }}</td>
                        <td class="px-4 py-2 border border-white">{{ $customer->name }}</td>
                        <td class="px-4 py-2 border border-white">{{ $customer->whatsapp_number }}</td>
                        <td class="px-4 py-2 border border-white capitalize">
                            <span
                                class="px-2 py-1 rounded text-sm
                            {{ $customer->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $customer->role }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <div class="flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.pelanggan.edit', $customer) }}"
                                    class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.pelanggan.destroy', $customer) }}" method="POST"
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
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada pelanggan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="lg:hidden space-y-4 fade-up animate-on-scroll">
        @forelse($customers as $customer)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $customer->name }}</h3>
                        <p class="text-xs text-gray-500">ID: {{ $customer->id }}</p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold capitalize
                        {{ $customer->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $customer->role }}
                    </span>
                </div>

                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        <span class="text-gray-700">{{ $customer->whatsapp_number }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.pelanggan.edit', $customer) }}"
                        class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition inline-flex items-center justify-center gap-1">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.pelanggan.destroy', $customer) }}" method="POST"
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
                Belum ada pelanggan
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-end">
        {{ $customers->links() }}
    </div>
@endsection
