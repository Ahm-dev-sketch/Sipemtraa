@extends('layouts.app')

@section('content')
    <!-- Header section untuk halaman create supir -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-user-plus text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Tambah Supir Baru</div>
                <div class="text-sm text-gray-500 font-normal">Isi form untuk menambahkan data supir</div>
            </div>
        </h1>
    </div>
    <!-- Container form utama -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
        <!-- Header form -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-id-card text-green-600"></i>
                Informasi Supir
            </h2>
        </div>

        <!-- Form untuk membuat supir baru -->
        <form id="formSupir" action="{{ route('admin.supir.store') }}" method="POST" class="p-6">
            @csrf
            <!-- Grid layout untuk form fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Field untuk input nama supir -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        Nama Supir
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                        placeholder="Masukkan nama lengkap supir"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Field untuk input nomor telepon supir -->
                <div class="md:col-span-2">
                    <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                        Nomor Telepon
                    </label>
                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}"
                        placeholder="Contoh: 08123456789 (opsional)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('telepon')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Field untuk memilih mobil yang dikendarai supir -->
                <div class="md:col-span-2">
                    <label for="mobil_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-bus-alt text-gray-400 mr-1"></i>
                        Mobil yang Dikendarai
                    </label>
                    <select id="mobil_id" name="mobil_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="">-- Pilih Mobil --</option>

                        @foreach ($mobils as $mobil)
                            <option value="{{ $mobil->id }}" {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                {{ $mobil->merk }} - {{ $mobil->nomor_polisi }} ({{ $mobil->jenis }})
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
            </div>

            <!-- Action buttons untuk submit dan cancel -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all">
                    <i class="fas fa-save"></i>
                    Simpan Data Supir
                </button>
                <a href="{{ route('admin.supir') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>

    </div>
@endsection
