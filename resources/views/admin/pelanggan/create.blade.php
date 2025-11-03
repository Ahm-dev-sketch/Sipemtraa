@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
                <i class="fas fa-user-plus text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Tambah Pelanggan Baru</div>
                <div class="text-sm text-gray-500 font-normal">Isi form untuk menambahkan data pelanggan</div>
            </div>
        </h1>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
        <!-- Header Form -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-friends text-green-600"></i>
                Informasi Pelanggan
            </h2>
        </div>

        <!-- Form Body -->
        <form id="formPelanggan" action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name"
                        required placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nomor WhatsApp --}}
                <div class="md:col-span-2">
                    <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-whatsapp text-gray-400 mr-1"></i>
                        Nomor WhatsApp
                    </label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                        autocomplete="tel" required placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                        Role Pengguna
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User - Pelanggan</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin - Pengelola</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-gray-400 mr-1"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    @error('password')
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
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all">
                    <i class="fas fa-save"></i>
                    Simpan Data Pelanggan
                </button>
                <a href="{{ route('admin.pelanggan') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-all">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
