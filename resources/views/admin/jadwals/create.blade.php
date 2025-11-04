@extends('layouts.app')


@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-calendar-plus text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Tambah Jadwal Baru</div>
                <div class="text-sm text-gray-500 font-normal">Buat jadwal keberangkatan baru</div>
            </div>
        </h1>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-4xl">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-green-600"></i>
                Detail Jadwal
            </h2>
        </div>

        <form id="formJadwal" action="{{ route('admin.jadwals.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label for="rute_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-route text-gray-400 mr-1"></i>
                        Rute Perjalanan
                    </label>
                    <select id="rute_id" name="rute_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="" disabled selected>-- Pilih Rute --</option>

                        @foreach ($rutes as $rute)
                            <option value="{{ $rute->id }}">
                                {{ $rute->kota_asal }} → {{ $rute->kota_tujuan }} ({{ $rute->jarak_estimasi }})
                            </option>
                        @endforeach

                    </select>
                    @error('rute_id')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="mobil_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-bus text-gray-400 mr-1"></i>
                        Kendaraan
                    </label>
                    <select id="mobil_id" name="mobil_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="" disabled selected>-- Pilih Mobil --</option>

                        @foreach ($mobils as $mobil)
                            <option value="{{ $mobil->id }}">
                                {{ $mobil->merk }} - {{ $mobil->nomor_polisi }} ({{ $mobil->kapasitas }} kursi)
                            </option>
                        @endforeach

                    </select>
                    @error('mobil_id')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-day text-gray-400 mr-1"></i>
                        Tanggal Keberangkatan
                    </label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="jam" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                        Waktu Keberangkatan
                    </label>
                    <select id="jam" name="jam" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="" disabled selected>-- Pilih rute terlebih dahulu --</option>
                    </select>
                    @error('jam')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave text-gray-400 mr-1"></i>
                        Harga Tiket
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-semibold">Rp</span>
                        <input type="text" id="harga" name="harga" value="{{ old('harga') }}" required
                            pattern="[0-9.]+" title="Masukkan angka dengan/tanpa titik pemisah"
                            class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                            placeholder="170.000">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        Contoh: 170000 atau 170.000
                    </p>
                    @error('harga')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all">
                    <i class="fas fa-save"></i>
                    Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwals') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>

    </div>
@endsection
