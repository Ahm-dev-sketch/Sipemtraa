@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-edit text-blue-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Edit Mobil</div>
                <div class="text-sm text-gray-500 font-normal">Perbarui informasi data mobil</div>
            </div>
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
        <!-- Header Form -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-bus-alt text-blue-600"></i>
                Informasi Mobil
            </h2>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.mobil.update', $mobil) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nomor Polisi --}}
                <div class="md:col-span-2">
                    <label for="nomor_polisi" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-gray-400 mr-1"></i>
                        Nomor Polisi
                    </label>
                    <input type="text" id="nomor_polisi" name="nomor_polisi"
                        value="{{ old('nomor_polisi', $mobil->nomor_polisi) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('nomor_polisi')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Merk --}}
                <div>
                    <label for="merk" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-gray-400 mr-1"></i>
                        Merk
                    </label>
                    <input type="text" id="merk" name="merk" value="{{ old('merk', $mobil->merk) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('merk')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jenis --}}
                <div>
                    <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-car text-gray-400 mr-1"></i>
                        Jenis Mobil
                    </label>
                    <input type="text" id="jenis" name="jenis" value="{{ old('jenis', $mobil->jenis) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('jenis')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users text-gray-400 mr-1"></i>
                        Kapasitas Penumpang
                    </label>
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $mobil->kapasitas) }}"
                        required min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('kapasitas')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tahun --}}
                <div>
                    <label for="tahun" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-gray-400 mr-1"></i>
                        Tahun Produksi
                    </label>
                    <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $mobil->tahun) }}" required
                        min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('tahun')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                        Status Operasional
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="aktif" {{ old('status', $mobil->status) == 'aktif' ? 'selected' : '' }}>Aktif
                        </option>
                        <option value="tidak aktif" {{ old('status', $mobil->status) == 'tidak aktif' ? 'selected' : '' }}>
                            Tidak Aktif (Maintenance/Perbaikan)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.mobil') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
