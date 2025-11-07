@extends('layouts.app')

@section('content')
    <!-- Forgot Password Form Section: Form untuk meminta reset password dengan input nomor WhatsApp untuk menerima OTP -->
    <div
        class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 relative overflow-hidden">

        <div class="absolute top-0 left-0 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 2s;"></div>
        <div class="w-full max-w-5xl relative z-10">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-teal-100/50">
                <div class="grid md:grid-cols-2 gap-0">

                    <div class="p-8 md:p-12">

                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-500 mb-4 shadow-lg shadow-teal-500/30">
                                <i class="fas fa-mobile-alt text-white text-2xl"></i>
                            </div>
                            <h2
                                class="text-3xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 bg-clip-text text-transparent mb-2">
                                Lupa Kata Sandi
                            </h2>
                            <p class="text-gray-600">Masukkan nomor WhatsApp Anda untuk menerima kode OTP</p>
                        </div>


                        @if (session('status'))
                            <div
                                class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    <p class="text-green-700 font-medium">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif



                        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-teal-500 mr-2"></i>Nomor WhatsApp
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fab fa-whatsapp text-teal-500 text-xl"></i>
                                    </div>
                                    <input type="text" name="whatsapp_number" placeholder="Contoh: 081234567890" required
                                        value="{{ old('whatsapp_number') }}"
                                        class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-400 focus:ring-4 focus:ring-teal-100 transition-all duration-300 text-gray-700">
                                </div>
                                @error('whatsapp_number')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                    <i class="fas fa-info-circle text-teal-500"></i>
                                    Kode OTP akan dikirim ke nomor WhatsApp ini
                                </p>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 hover:from-teal-600 hover:via-cyan-600 hover:to-blue-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 group">
                                <i
                                    class="fas fa-paper-plane group-hover:translate-x-1 transition-transform duration-300"></i>
                                Kirim Kode OTP
                            </button>

                            <div class="text-center pt-4">
                                <a href="{{ route('login') }}"
                                    class="text-sm text-gray-600 hover:text-teal-600 transition-colors duration-300 flex items-center justify-center gap-2 group">
                                    <i
                                        class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform duration-300"></i>
                                    Kembali ke Login
                                </a>
                            </div>
                        </form>


                        <div
                            class="mt-8 p-4 bg-gradient-to-r from-teal-50/50 to-cyan-50/50 rounded-xl border border-teal-100">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-lightbulb text-teal-500 text-lg mt-0.5"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Tips Keamanan</h4>
                                    <p class="text-xs text-gray-600">Pastikan nomor WhatsApp yang Anda masukkan masih aktif
                                        dan terdaftar di sistem kami.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="hidden md:flex items-center justify-center p-12 bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-600 relative overflow-hidden">

                        <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10 text-center text-white">

                            <div class="mb-8 relative">
                                <div
                                    class="w-48 h-48 mx-auto bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30">
                                    <i class="fab fa-whatsapp text-white text-8xl animate-pulse"></i>
                                </div>
                                <div
                                    class="absolute -bottom-4 -right-4 w-20 h-20 bg-green-400 rounded-full flex items-center justify-center shadow-xl animate-bounce">
                                    <i class="fas fa-envelope text-white text-3xl"></i>
                                </div>
                            </div>

                            <h3 class="text-2xl font-bold mb-4">Lupa Kata Sandi?</h3>
                            <p class="text-white/90 mb-8 px-4">Jangan khawatir! Kami akan mengirimkan kode OTP ke WhatsApp
                                Anda</p>

                            <div class="space-y-4">
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-clock text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Cepat & Mudah</h4>
                                            <p class="text-xs text-white/80">Kode OTP terkirim dalam hitungan detik</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-shield-alt text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Aman & Terpercaya</h4>
                                            <p class="text-xs text-white/80">Proses verifikasi berlapis</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all duration-300">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                            <i class="fas fa-check-double text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm">Verifikasi Instan</h4>
                                            <p class="text-xs text-white/80">Langsung reset password Anda</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                <h4 class="font-semibold text-sm mb-3 flex items-center justify-center gap-2">
                                    <i class="fas fa-list-ol"></i>
                                    Langkah Mudah
                                </h4>
                                <div class="space-y-2 text-xs text-white/90 text-left">
                                    <div class="flex items-start gap-2">
                                        <span
                                            class="flex items-center justify-center w-5 h-5 bg-white/20 rounded-full shrink-0 text-xs font-bold">1</span>
                                        <p>Masukkan nomor WhatsApp</p>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span
                                            class="flex items-center justify-center w-5 h-5 bg-white/20 rounded-full shrink-0 text-xs font-bold">2</span>
                                        <p>Terima kode OTP via WhatsApp</p>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span
                                            class="flex items-center justify-center w-5 h-5 bg-white/20 rounded-full shrink-0 text-xs font-bold">3</span>
                                        <p>Reset password Anda</p>
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
