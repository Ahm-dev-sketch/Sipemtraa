@extends('layouts.app')

@section('content')
    <!-- Login Page Container -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 -z-10"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-5 -z-10"></div>
        <div
            class="absolute top-20 left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-40 right-10 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000">
        </div>
        <div class="max-w-6xl w-full">
            <!-- Login Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden fade-up animate-on-scroll">
                <div class="flex flex-col md:flex-row">

                    <!-- Login Form Section -->
                    <div class="md:w-1/2 p-8 md:p-12 fade-right animate-on-scroll">
                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl mb-4">
                                <i class="fas fa-sign-in-alt text-3xl text-blue-600"></i>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                                Selamat <span
                                    class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Datang</span>
                            </h2>
                            <p class="text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('register') }}"
                                    class="text-blue-600 hover:text-indigo-600 font-semibold transition-colors">
                                    Daftar di sini
                                </a>
                            </p>
                        </div>


                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                                    <div class="flex-1">

                                        @foreach ($errors->all() as $error)
                                            <p class="text-sm text-red-700">{{ $error }}</p>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif


                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf


                            @if (request('redirect'))
                                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                            @endif


                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-1 text-blue-600"></i>
                                    Nomor WhatsApp
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-mobile-alt text-gray-400"></i>
                                    </div>
                                    <input type="tel" name="whatsapp_number" placeholder="08123456789"
                                        value="{{ old('whatsapp_number') }}" autocomplete="tel" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-1 text-indigo-600"></i>
                                    Kata Sandi
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password" placeholder="Masukkan kata sandi"
                                        autocomplete="current-password" required
                                        class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-200 rounded-xl
                                            focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500
                                            transition-all duration-200 bg-gray-50 hover:bg-white">
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-blue-600 hover:text-indigo-600 font-semibold transition-colors">
                                    Lupa kata sandi?
                                </a>
                            </div>

                            <button type="submit"
                                class="group w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600
                                    text-white rounded-xl hover:from-blue-700 hover:to-indigo-700
                                    transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105
                                    font-bold flex items-center justify-center gap-2">
                                <span>Masuk Sekarang</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>


                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-center text-sm text-gray-600">
                                Dengan masuk, Anda menyetujui
                                <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> kami
                            </p>
                        </div>
                    </div>

                    <!-- Company Info Section -->
                    <div
                        class="md:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 flex flex-col justify-center items-center p-8 md:p-12 text-white relative overflow-hidden fade-left animate-on-scroll">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mb-48"></div>
                        <div class="relative z-10 text-center">
                            <div
                                class="inline-flex items-center justify-center w-32 h-32 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 border-2 border-white/20 shadow-2xl">
                                <i class="fas fa-bus text-7xl"></i>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-bold mb-4">
                                PT. Pelita Transport Prima
                            </h3>
                            <p class="text-xl text-blue-100 mb-8">
                                Travel Terbaik Untuk Anda
                            </p>
                            <div class="space-y-4 text-left max-w-xs mx-auto">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-check text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Booking Online</p>
                                        <p class="text-sm text-blue-100">Pesan tiket kapan saja</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-shield-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Aman & Nyaman</p>
                                        <p class="text-sm text-blue-100">Perjalanan yang terpercaya</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-clock text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Tepat Waktu</p>
                                        <p class="text-sm text-blue-100">Jadwal yang teratur</p>
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
