@extends('layouts.app')

@section('content')
    <!-- Header section untuk halaman edit pelanggan -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i class="fas fa-user-edit text-blue-600 text-2xl"></i>
            </div>
            <div>
                <div class="text-gray-900">Edit Pelanggan</div>
                <div class="text-sm text-gray-500 font-normal">Perbarui informasi data pelanggan</div>
            </div>
        </h1>
    </div>
    <!-- Container form utama -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl">
        <!-- Header form -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-friends text-blue-600"></i>
                Informasi Pelanggan
            </h2>
        </div>

        <!-- Form untuk mengedit pelanggan -->
        <form action="{{ route('admin.pelanggan.update', $customer) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <!-- Grid layout untuk form fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Field untuk input nama lengkap -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Field untuk input nomor WhatsApp -->
                <div class="md:col-span-2">
                    <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-whatsapp text-gray-400 mr-1"></i>
                        Nomor WhatsApp
                    </label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number"
                        value="{{ old('whatsapp_number', $customer->whatsapp_number) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Field untuk memilih role pengguna -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                        Role Pengguna
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="user" {{ old('role', $customer->role) == 'user' ? 'selected' : '' }}>User -
                            Pelanggan</option>
                        <option value="admin" {{ old('role', $customer->role) == 'admin' ? 'selected' : '' }}>Admin -
                            Pengelola</option>
                    </select>
                    @error('role')
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
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
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
