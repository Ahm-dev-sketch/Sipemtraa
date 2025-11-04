@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-plus-circle text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Tambah Rute Baru</div>
                <div class="text-sm text-gray-500 font-normal">Isi form untuk menambahkan rute perjalanan</div>
            </div>
        </h1>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-route text-green-600"></i>
                Informasi Rute
            </h2>
        </div>

        <form id="formRute" action="{{ route('admin.rute.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="kota_asal" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                        Kota Asal
                    </label>
                    <input type="text" id="kota_asal" name="kota_asal" value="{{ old('kota_asal') }}" required
                        placeholder="Contoh: Jakarta"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('kota_asal')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="kota_tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marked-alt text-gray-400 mr-1"></i>
                        Kota Tujuan
                    </label>
                    <input type="text" id="kota_tujuan" name="kota_tujuan" value="{{ old('kota_tujuan') }}" required
                        placeholder="Contoh: Bandung"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('kota_tujuan')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="jarak_estimasi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-road text-gray-400 mr-1"></i>
                        Jarak / Estimasi Waktu
                    </label>
                    <input type="text" id="jarak_estimasi" name="jarak_estimasi" value="{{ old('jarak_estimasi') }}"
                        required placeholder="Contoh: 425 km / 6 jam"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('jarak_estimasi')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="harga_tiket" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave text-gray-400 mr-1"></i>
                        Harga Tiket
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-semibold">Rp</span>
                        <input type="text" id="harga_tiket" name="harga_tiket" value="{{ old('harga_tiket') }}" required
                            placeholder="170.000" pattern="[0-9.]+" title="Masukkan angka dengan/tanpa titik pemisah"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        Contoh: 170000 atau 170.000
                    </p>
                    @error('harga_tiket')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="jam_keberangkatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                        Jam Keberangkatan
                    </label>
                    <input type="time" id="jam_keberangkatan" name="jam_keberangkatan"
                        value="{{ old('jam_keberangkatan') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('jam_keberangkatan')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="status_rute" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                        Status Rute
                    </label>
                    <select id="status_rute" name="status_rute" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="aktif" {{ old('status_rute') == 'aktif' ? 'selected' : '' }}>Aktif - Rute Tersedia
                        </option>
                        <option value="nonaktif" {{ old('status_rute') == 'nonaktif' ? 'selected' : '' }}>Nonaktif - Rute
                            Ditutup</option>
                    </select>
                    @error('status_rute')
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
                    Simpan Data Rute
                </button>
                <a href="{{ route('admin.rute') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>

    </div>
@endsection
