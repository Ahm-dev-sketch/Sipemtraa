@extends('layouts.app')

@section('content')
    <!-- OTP Verification Section: Form verifikasi OTP untuk menyelesaikan pendaftaran akun baru -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 -z-10"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-5 -z-10"></div>
        <div
            class="absolute top-20 left-10 w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-40 right-10 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000">
        </div>
        <div class="max-w-6xl w-full">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden fade-up animate-on-scroll">
                <div class="flex flex-col md:flex-row">

                    <div class="md:w-1/2 p-8 md:p-12 fade-right animate-on-scroll">
                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl mb-4">
                                <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                                Verifikasi <span
                                    class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">OTP</span>
                            </h2>
                            <p class="text-gray-600">
                                Kode OTP telah dikirim ke WhatsApp
                            </p>
                            <div
                                class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-xl border border-purple-200">
                                <i class="fab fa-whatsapp text-purple-600"></i>
                                <strong class="text-purple-900">{{ $whatsapp_number }}</strong>
                            </div>
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



                        @if (session('status'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif


                        <form method="POST" action="{{ route('register.verify.submit') }}" class="space-y-8">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-4 text-center">
                                    <i class="fas fa-key mr-1 text-purple-600"></i>
                                    Masukkan Kode OTP (6 Digit)
                                </label>
                                <div class="relative">
                                    <input type="text" name="otp_code" placeholder="• • • • • •" required maxlength="6"
                                        inputmode="numeric" pattern="[0-9]{6}"
                                        class="w-full px-6 py-5 border-2 border-gray-200 rounded-2xl
                                            focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white
                                            text-center text-3xl tracking-[1em] font-bold"
                                        autocomplete="off" autofocus>
                                </div>
                                @error('otp_code')
                                    <p class="mt-3 text-sm text-red-600 flex items-center justify-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-600 flex items-center justify-center gap-2">
                                        <i class="fas fa-clock text-purple-600"></i>
                                        Kode berlaku selama <strong>5 menit</strong>
                                    </p>
                                </div>
                            </div>

                            <button type="submit"
                                class="group w-full py-4 px-6 bg-gradient-to-r from-purple-600 to-pink-600
                                    text-white rounded-xl hover:from-purple-700 hover:to-pink-700
                                    transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105
                                    font-bold flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>Verifikasi & Selesaikan Pendaftaran</span>
                            </button>
                        </form>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-center text-sm text-gray-600 mb-3">Tidak menerima kode OTP?</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <input type="hidden" name="name" value="{{ old('name') }}">
                                <input type="hidden" name="email" value="{{ old('email') }}">
                                <input type="hidden" name="whatsapp_number" value="{{ old('whatsapp_number') }}">
                                <input type="hidden" name="password" value="{{ old('password') }}">
                                <input type="hidden" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}">
                                <button type="submit"
                                    class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700
                                        rounded-xl transition-all duration-300 font-semibold flex items-center justify-center gap-2">
                                    <i class="fas fa-redo"></i>
                                    <span>Kirim Ulang Kode OTP</span>
                                </button>
                            </form>

                        </div>
                    </div>

                    <div
                        class="md:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex flex-col justify-center items-center p-8 md:p-12 text-white relative overflow-hidden fade-left animate-on-scroll">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mb-48"></div>
                        <div class="relative z-10 text-center">
                            <div
                                class="inline-flex items-center justify-center w-32 h-32 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 border-2 border-white/20 shadow-2xl animate-pulse">
                                <i class="fas fa-mobile-alt text-7xl"></i>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-bold mb-4">
                                Verifikasi Keamanan
                            </h3>
                            <p class="text-xl text-purple-100 mb-8">
                                Langkah Terakhir Pendaftaran
                            </p>
                            <div class="space-y-4 text-left max-w-xs mx-auto">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-1 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Buka WhatsApp</p>
                                        <p class="text-sm text-purple-100">Cek pesan masuk dari sistem</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-2 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Salin Kode OTP</p>
                                        <p class="text-sm text-purple-100">6 digit angka verifikasi</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-3 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Masukkan & Verifikasi</p>
                                        <p class="text-sm text-purple-100">Selesaikan pendaftaran</p>
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
