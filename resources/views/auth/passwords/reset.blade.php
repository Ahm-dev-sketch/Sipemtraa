@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-orange-50 via-red-50 to-pink-50 relative overflow-hidden">

        <div class="absolute top-0 left-0 w-96 h-96 bg-orange-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-red-400/20 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-400/10 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 2s;"></div>
        <div class="w-full max-w-5xl relative z-10">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-orange-100/50">
                <div class="grid md:grid-cols-2 gap-0">

                    <div class="p-8 md:p-12">

                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 via-red-500 to-pink-500 mb-4 shadow-lg shadow-orange-500/30">
                                <i class="fas fa-key text-white text-2xl"></i>
                            </div>
                            <h2
                                class="text-3xl font-bold bg-gradient-to-r from-orange-600 via-red-600 to-pink-600 bg-clip-text text-transparent mb-2">
                                Reset Kata Sandi
                            </h2>
                            <p class="text-gray-600">Masukkan kode OTP dan kata sandi baru untuk akun Anda</p>
                        </div>


                        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="whatsapp_number"
                                value="{{ $whatsapp_number ?? old('whatsapp_number') }}">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-orange-500 mr-2"></i>Nomor WhatsApp
                                </label>
                                <div class="relative">
                                    <input type="text" name="whatsapp_number"
                                        value="{{ $whatsapp_number ?? old('whatsapp_number') }}" readonly
                                        class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-300 pl-11 text-gray-700 font-medium cursor-not-allowed">
                                    <i
                                        class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-shield-alt text-orange-500 mr-2"></i>Kode OTP
                                </label>
                                <input type="text" name="otp_code" placeholder="Masukkan 6 digit kode OTP"
                                    autocomplete="one-time-code" required maxlength="6"
                                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-center text-2xl font-bold tracking-widest">
                                @error('otp_code')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock text-orange-500 mr-2"></i>Kata Sandi Baru
                                </label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                    autocomplete="new-password" required
                                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-300">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock text-orange-500 mr-2"></i>Konfirmasi Kata Sandi
                                </label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru"
                                    autocomplete="new-password" required
                                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-300">
                                @error('whatsapp_number')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 hover:from-orange-600 hover:via-red-600 hover:to-pink-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 group">
                                <i class="fas fa-key group-hover:rotate-12 transition-transform duration-300"></i>
                                Reset Password
                            </button>

                            <div class="text-center pt-4">
                                <a href="{{ route('login') }}"
                                    class="text-sm text-gray-600 hover:text-orange-600 transition-colors duration-300 flex items-center justify-center gap-2 group">
                                    <i
                                        class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform duration-300"></i>
                                    Kembali ke Login
                                </a>
                            </div>
                        </form>

                    </div>

                    <div
                        class="hidden md:flex items-center justify-center p-12 bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 relative overflow-hidden">

                        <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10 text-center text-white">

                            <div class="mb-8 relative">
                                <div
                                    class="w-48 h-48 mx-auto bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 animate-pulse">
                                    <i class="fas fa-shield-alt text-white text-7xl"></i>
                                </div>
                                <div
                                    class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-xl animate-bounce">
                                    <i class="fas fa-check text-white text-2xl"></i>
                                </div>
                            </div>

                            <h3 class="text-2xl font-bold mb-4">Keamanan Terjamin</h3>
                            <p class="text-white/90 mb-8 px-4">Gunakan kata sandi yang kuat dengan kombinasi huruf, angka,
                                dan simbol</p>

                            <div class="space-y-4">
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-lock text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Minimal 8 Karakter</h4>
                                            <p class="text-xs text-white/80">Gunakan kombinasi yang kuat</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-user-shield text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Data Terenkripsi</h4>
                                            <p class="text-xs text-white/80">Keamanan data terjamin</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-mobile-alt text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Verifikasi OTP</h4>
                                            <p class="text-xs text-white/80">Keamanan berlapis untuk akun Anda</p>
                                        </div>
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
