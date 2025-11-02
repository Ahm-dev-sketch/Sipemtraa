@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div
            class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl zoom-in animate-on-scroll">

            {{-- Left side: Form --}}
            <div class="md:w-1/2 p-8 fade-right animate-on-scroll">
                <h2 class="text-2xl font-bold mb-4">Masuk Akun</h2>
                <p class="mb-6">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar di sini</a>
                </p>

                {{-- pesan error login --}}
                @if ($errors->has('email'))
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-600 rounded">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- WhatsApp Number --}}
                    <input type="tel" name="whatsapp_number" placeholder="Nomor WA" value="{{ old('whatsapp_number') }}"
                        autocomplete="tel" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                    {{-- Password --}}
                    <input type="password" name="password" placeholder="Kata Sandi" autocomplete="current-password" required
                        class="w-full border p-3 rounded focus:outline-none focus:ring focus:ring-blue-300">

                    <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition">
                        Login
                    </button>
                </form>

                {{-- Forgot Password Link --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                        Lupa kata sandi?
                    </a>
                </div>
            </div>

            {{-- Right side: Illustration --}}
            <div class="md:w-1/2 bg-blue-50 flex justify-center items-center p-6 fade-left animate-on-scroll">
                <div class="text-center">
                    <div class="bg-blue-100 w-64 h-64 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bus text-blue-600 text-8xl"></i>
                    </div>
                    <h3 class="mt-4 font-bold text-lg">PT. Pelita Tran Prima</h3>
                    <p class="text-gray-500">Travel Terbaik Untuk Anda</p>
                </div>
            </div>
        </div>
    </div>
@endsection
