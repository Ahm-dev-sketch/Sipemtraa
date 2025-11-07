@extends('layouts.app')

@section('content')
    <!-- Register Form Section: Form pendaftaran akun baru dengan input nama, WhatsApp, password, dan konfirmasi password -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 -z-10"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-5 -z-10"></div>
        <div
            class="absolute top-20 left-10 w-72 h-72 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-40 right-10 w-72 h-72 bg-emerald-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000">
        </div>
        <div class="max-w-6xl w-full">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden fade-up animate-on-scroll">
                <div class="flex flex-col md:flex-row">

                    <div class="md:w-1/2 p-8 md:p-12 fade-right animate-on-scroll">
                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl mb-4">
                                <i class="fas fa-user-plus text-3xl text-green-600"></i>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                                Daftar <span
                                    class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent">Sekarang</span>
                            </h2>
                            <p class="text-gray-600">
                                Sudah punya akun?
                                <a href="{{ route('login') }}"
                                    class="text-green-600 hover:text-emerald-600 font-semibold transition-colors">
                                    Login di sini
                                </a>
                            </p>
                        </div>


                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                                    <div class="flex-1">
                                        <ul class="space-y-1">

                                            @foreach ($errors->all() as $error)
                                                <li class="text-sm text-red-700">{{ $error }}</li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-1 text-green-600"></i>
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="name" placeholder="Masukkan nama lengkap"
                                        value="{{ old('name') }}" autocomplete="name" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-green-500/20 focus:border-green-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-1 text-emerald-600"></i>
                                    Nomor WhatsApp
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fab fa-whatsapp text-gray-400"></i>
                                    </div>
                                    <input type="tel" name="whatsapp_number" placeholder="08123456789"
                                        value="{{ old('whatsapp_number') }}" autocomplete="tel" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                                @error('whatsapp_number')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-1 text-teal-600"></i>
                                    Kata Sandi
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                                        autocomplete="new-password" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-check-circle mr-1 text-green-600"></i>
                                    Konfirmasi Kata Sandi
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-shield-alt text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
                                        autocomplete="new-password" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-green-500/20 focus:border-green-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                            </div>

                            <button type="submit"
                                class="group w-full py-4 px-6 bg-gradient-to-r from-green-600 to-emerald-600
                                    text-white rounded-xl hover:from-green-700 hover:to-emerald-700
                                    transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105
                                    font-bold flex items-center justify-center gap-2 mt-6">
                                <span>Daftar Sekarang</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>


                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-center text-sm text-gray-600">
                                Dengan mendaftar, Anda menyetujui
                                <a href="#" class="text-green-600 hover:underline">Syarat & Ketentuan</a> kami
                            </p>
                        </div>
                    </div>

                    <div
                        class="md:w-1/2 bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 flex flex-col justify-center items-center p-8 md:p-12 text-white relative overflow-hidden fade-left animate-on-scroll">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mb-48"></div>
                        <div class="relative z-10 text-center">
                            <div
                                class="inline-flex items-center justify-center w-32 h-32 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 border-2 border-white/20 shadow-2xl">
                                <i class="fas fa-user-check text-7xl"></i>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-bold mb-4">
                                Bergabung Bersama Kami
                            </h3>
                            <p class="text-xl text-green-100 mb-8">
                                Mulai Perjalanan Anda
                            </p>
                            <div class="space-y-4 text-left max-w-xs mx-auto">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-bolt text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Pendaftaran Cepat</p>
                                        <p class="text-sm text-green-100">Hanya butuh 2 menit</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-gift text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Promo Menarik</p>
                                        <p class="text-sm text-green-100">Dapatkan penawaran khusus</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-history text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Riwayat Perjalanan</p>
                                        <p class="text-sm text-green-100">Lacak semua booking Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
