<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>PT. PELITA TRAN PRIMA</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.webp') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="origin-trial" content="">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    @if (request()->is('/'))
        <link rel="preload" as="image" href="{{ asset('asset/home.webp') }}" fetchpriority="high"
            imagesrcset="{{ asset('asset/home.webp') }} 800w" imagesizes="(max-width: 800px) 100vw, 800px">
    @endif

    <link rel="preload" as="image" href="{{ asset('asset/logo.webp') }}">

    @if (app()->environment() !== 'testing')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </noscript>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    @if (auth()->check() && auth()->user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
</head>

<body class="bg-gray-100 m-0 p-0">

    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display: none;"></div>
    @endif


    @if (session('error'))
        <div data-error-message="{{ session('error') }}" style="display: none;"></div>
    @endif


    @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="flex min-h-screen relative">
            <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>
            <button id="menu-toggle"
                class="md:hidden fixed top-4 left-4 z-60 p-3 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white rounded-xl shadow-lg shadow-blue-500/30 focus:outline-none hover:shadow-xl hover:shadow-blue-500/40 hover:scale-110 active:scale-95 transition-all duration-300"
                aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <aside id="sidebar"
                class="w-72 min-h-screen bg-gradient-to-b from-[#0F172A] via-[#1E293B] to-[#0F172A] text-white flex flex-col transform -translate-x-full
                       md:translate-x-0 transition-all duration-500 ease-in-out md:static fixed z-50 top-0 left-0
                       shadow-[4px_0_16px_rgba(0,0,0,0.15)] md:shadow-[6px_0_20px_rgba(0,0,0,0.1)]
                       border-r border-slate-700/20">
                <div
                    class="p-5 border-b border-slate-600/20 bg-gradient-to-r from-slate-800/30 to-slate-900/20 backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('asset/logo.webp') }}" alt="Logo"
                            class="h-12 w-12 rounded-xl object-cover ring-2 ring-blue-400/30 shadow-md shadow-blue-500/10 transition-all duration-300 hover:ring-blue-400/50 hover:shadow-lg hover:shadow-blue-500/20"
                            width="48" height="48" loading="lazy">
                        <div>
                            <h1 class="text-base font-bold text-white leading-tight tracking-tight">PT. PELITA</h1>
                            <p class="text-xs text-blue-300 font-semibold tracking-wide">TRAN PRIMA</p>
                        </div>
                    </div>
                </div>
                <div
                    class="p-4 bg-gradient-to-r from-blue-600/8 via-indigo-600/8 to-purple-600/8 border-b border-slate-600/20 backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div
                                class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 flex items-center justify-center text-lg font-bold shadow-lg shadow-blue-500/20 ring-2 ring-blue-400/20 transition-all duration-300 hover:ring-blue-400/40 hover:shadow-xl hover:shadow-blue-500/30">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div
                                class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 border-slate-900 shadow-md shadow-emerald-400/40 animate-pulse">
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-300 flex items-center gap-1">
                                <i class="fas fa-shield-alt text-blue-400"></i>
                                Administrator
                            </p>
                        </div>
                    </div>
                </div>
                <nav
                    class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-thin scrollbar-thumb-slate-600/50 scrollbar-track-transparent hover:scrollbar-thumb-slate-600/70">
                    <a href="{{ route('admin.dashboard') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-blue-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-blue-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-home-alt text-lg {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-400 group-hover:text-blue-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Dashboard</span>
                    </a>
                    <div class="py-2">
                        <div class="h-px bg-gradient-to-r from-transparent via-slate-600/30 to-transparent"></div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-3 mb-2 px-3">
                            Operasional</p>
                    </div>
                    @php
                        $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
                    @endphp
                    <a href="{{ route('admin.bookings') }}"
                        class="group flex items-center justify-between py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.bookings') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-purple-400/50' }}">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.bookings') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-purple-600/20' }} transition-all duration-300">
                                <i
                                    class="fas fa-ticket-alt text-lg {{ request()->routeIs('admin.bookings') ? 'text-white' : 'text-purple-400 group-hover:text-purple-300' }}"></i>
                            </div>
                            <span
                                class="font-medium text-sm {{ request()->routeIs('admin.bookings') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Pemesanan</span>
                        </div>

                        @if ($pendingBookingsCount > 0)
                            <span
                                class="flex items-center justify-center min-w-[22px] h-5.5 px-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full shadow-lg shadow-red-500/50 animate-pulse">
                                {{ $pendingBookingsCount }}
                            </span>
                        @endif

                    </a>
                    <a href="{{ route('admin.jadwals') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.jadwals') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-green-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.jadwals') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-green-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-calendar-check text-lg {{ request()->routeIs('admin.jadwals') ? 'text-white' : 'text-green-400 group-hover:text-green-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.jadwals') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Penjadwalan</span>
                    </a>
                    @php
                        $belumBayarCount = \App\Models\Booking::where('payment_status', 'belum_bayar')
                            ->where('status', 'setuju')
                            ->count();
                        $sudahBayarPendingCount = \App\Models\Booking::where('payment_status', 'sudah_bayar')
                            ->where('status', 'pending')
                            ->count();
                        $totalPaymentNotif = $belumBayarCount + $sudahBayarPendingCount;
                    @endphp
                    <a href="{{ route('admin.pembayaran') }}"
                        class="group flex items-center justify-between py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.pembayaran') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-yellow-400/50' }}">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.pembayaran') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-yellow-600/20' }} transition-all duration-300">
                                <i
                                    class="fas fa-wallet text-lg {{ request()->routeIs('admin.pembayaran') ? 'text-white' : 'text-yellow-400 group-hover:text-yellow-300' }}"></i>
                            </div>
                            <span
                                class="font-medium text-sm {{ request()->routeIs('admin.pembayaran') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Pembayaran</span>
                        </div>

                        @if ($totalPaymentNotif > 0)
                            <span
                                class="flex items-center justify-center min-w-[22px] h-5.5 px-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold rounded-full shadow-lg shadow-orange-500/50">
                                {{ $totalPaymentNotif }}
                            </span>
                        @endif

                    </a>
                    <div class="py-2">
                        <div class="h-px bg-gradient-to-r from-transparent via-slate-600/30 to-transparent"></div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-3 mb-2 px-3">Master
                            Data</p>
                    </div>
                    <a href="{{ route('admin.pelanggan') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.pelanggan') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-indigo-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.pelanggan') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-indigo-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-users text-lg {{ request()->routeIs('admin.pelanggan') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.pelanggan') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Pelanggan</span>
                    </a>
                    <a href="{{ route('admin.rute') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.rute') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-cyan-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.rute') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-cyan-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-route text-lg {{ request()->routeIs('admin.rute') ? 'text-white' : 'text-cyan-400 group-hover:text-cyan-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.rute') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Rute
                            Perjalanan</span>
                    </a>
                    <a href="{{ route('admin.mobil') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.mobil') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-teal-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.mobil') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-teal-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-bus text-lg {{ request()->routeIs('admin.mobil') ? 'text-white' : 'text-teal-400 group-hover:text-teal-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.mobil') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Armada
                            Mobil</span>
                    </a>
                    <a href="{{ route('admin.supir') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.supir') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-pink-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.supir') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-pink-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-id-card-alt text-lg {{ request()->routeIs('admin.supir') ? 'text-white' : 'text-pink-400 group-hover:text-pink-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.supir') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Data
                            Supir</span>
                    </a>
                    <div class="py-2">
                        <div class="h-px bg-gradient-to-r from-transparent via-slate-600/30 to-transparent"></div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-3 mb-2 px-3">Laporan
                        </p>
                    </div>
                    <a href="{{ route('admin.laporan') }}"
                        class="group flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-300 ease-out {{ request()->routeIs('admin.laporan') ? 'bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 shadow-lg shadow-blue-500/30 scale-[1.02]' : 'hover:bg-slate-800/50 hover:shadow-sm hover:translate-x-1 hover:border-l-2 hover:border-emerald-400/50' }}">
                        <div
                            class="flex items-center justify-center w-9 h-9 rounded-lg {{ request()->routeIs('admin.laporan') ? 'bg-white/20' : 'bg-slate-800/60 group-hover:bg-emerald-600/20' }} transition-all duration-300">
                            <i
                                class="fas fa-chart-line text-lg {{ request()->routeIs('admin.laporan') ? 'text-white' : 'text-emerald-400 group-hover:text-emerald-300' }}"></i>
                        </div>
                        <span
                            class="font-medium text-sm {{ request()->routeIs('admin.laporan') ? 'text-white font-semibold' : 'text-slate-300 group-hover:text-white' }}">Pendapatan</span>
                    </a>
                </nav>
                <div
                    class="p-4 border-t border-slate-700/30 bg-gradient-to-r from-slate-900/60 to-slate-800/40 backdrop-blur-sm">

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button id="logout-btn" type="button"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 py-3 px-4 rounded-xl font-medium text-sm shadow-lg shadow-red-600/40 transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-red-600/50 active:scale-[0.98]">
                            <i class="fas fa-sign-out-alt text-base"></i>
                            <span>Logout</span>
                        </button>
                    </form>

                </div>
            </aside>
            <main class="flex-1 bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 min-h-screen">
                <div class="bg-white/80 backdrop-blur-sm border-b border-slate-200 shadow-sm sticky top-0 z-30">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 sm:gap-4 flex-1 min-w-0 ml-14 md:ml-0">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <div
                                    class="w-1 h-6 sm:h-8 bg-gradient-to-b from-cyan-500 to-blue-600 rounded-full shrink-0">
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-base sm:text-xl font-bold text-slate-800 truncate">
                                        @yield('page-title', 'Dashboard')
                                    </h2>
                                    <p class="text-xs text-slate-500 truncate hidden sm:block">
                                        @yield('page-subtitle', 'Selamat datang di panel admin')
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                            <div class="text-right hidden lg:block">
                                <p class="text-sm font-semibold text-slate-700 truncate max-w-[150px]">
                                    {{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</p>
                            </div>
                            <div
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 flex items-center justify-center text-white text-sm sm:text-base font-bold shadow-lg ring-2 ring-blue-400/30 shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6 md:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        <nav id="navbar"
            class="bg-white/70 backdrop-blur-xl text-gray-900 shadow-sm fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b border-blue-100/50">
            <div class="container mx-auto flex justify-between items-center px-4 py-3.5 md:px-6">

                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-400/40 via-indigo-400/30 to-purple-400/40 rounded-xl blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <img src="{{ asset('asset/logo.webp') }}" alt="Logo"
                            class="relative h-12 w-12 rounded-xl object-cover shadow-sm group-hover:shadow-md transition-all duration-300"
                            width="48" height="48" loading="lazy">
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-base md:text-lg font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">PT.
                            PELITA</span>
                        <span class="text-xs font-semibold text-gray-500">TRAN PRIMA</span>
                    </div>
                </a>

                <button id="menu-toggle"
                    class="md:hidden focus:outline-none p-2.5 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-white hover:shadow-lg hover:shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>

                <div class="hidden md:flex md:items-center gap-1.5">
                    <a href="{{ route('home') }}"
                        class="relative group px-4 py-2.5 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        <span class="relative z-10">Home</span>

                        @if (request()->routeIs('home'))
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-indigo-500/5 to-purple-500/5 rounded-xl border border-blue-200/50">
                            </div>
                        @endif

                    </a>
                    <a href="{{ route('jadwal') }}"
                        class="relative group px-4 py-2.5 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('jadwal') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        <span class="relative z-10">Jadwal</span>

                        @if (request()->routeIs('jadwal'))
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-indigo-500/5 to-purple-500/5 rounded-xl border border-blue-200/50">
                            </div>
                        @endif

                    </a>
                    <a href="{{ auth()->check() ? route('pesan') : route('login') }}"
                        class="relative group px-4 py-2.5 rounded-xl font-medium transition-all duration-300 {{ request()->routeIs('pesan') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        <span class="relative z-10">Pesan Tiket</span>

                        @if (request()->routeIs('pesan'))
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-indigo-500/5 to-purple-500/5 rounded-xl border border-blue-200/50">
                            </div>
                        @endif

                    </a>
                    <a href="{{ auth()->check() ? route('riwayat') : route('login') }}"
                        class="relative group px-4 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('riwayat') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                        <span class="relative z-10">Riwayat</span>
                        @auth
                            @php
                                $userPendingCount = \App\Models\Booking::where('user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp

                            @if ($userPendingCount > 0)
                                <span
                                    class="relative z-10 bg-gradient-to-r from-red-500 via-pink-500 to-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md animate-pulse">
                                    {{ $userPendingCount }}
                                </span>
                            @endif

                        @endauth

                        @if (request()->routeIs('riwayat'))
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-indigo-500/5 to-purple-500/5 rounded-xl border border-blue-200/50">
                            </div>
                        @endif

                    </a>
                    @guest
                        <a href="{{ route('login') }}"
                            class="ml-4 px-6 py-2.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all duration-300 shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                    @else
                        @php
                            $firstName = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
                            $initial = strtoupper(substr(auth()->user()->name, 0, 1));
                        @endphp
                        <div class="relative ml-4">
                            <button type="button" id="user-menu-btn"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl bg-gradient-to-br from-blue-50/50 via-indigo-50/40 to-purple-50/50 border border-blue-200/30 hover:border-blue-300/50 hover:shadow-sm transition-all duration-300 cursor-pointer group backdrop-blur-sm">
                                <div class="relative shrink-0">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs shadow-sm group-hover:shadow-md transition-all duration-300">
                                        {{ $initial }}
                                    </div>
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-white">
                                    </div>
                                </div>
                                <div class="hidden sm:flex items-center gap-1">
                                    <span class="text-sm text-gray-500 font-medium">Hi,</span>
                                    <strong class="text-sm text-gray-800 font-semibold">{{ $firstName }}</strong>
                                </div>
                                <i
                                    class="fas fa-chevron-down text-gray-400 text-xs group-hover:text-blue-600 transition-colors group-hover:rotate-180 duration-300"></i>
                            </button>

                            <div id="user-dropdown"
                                class="hidden absolute right-0 top-full mt-2 w-60 bg-white/95 backdrop-blur-lg text-gray-700 rounded-xl shadow-2xl border border-gray-200/50 py-2 z-50 transition-all duration-300 opacity-0 scale-95">
                                <div
                                    class="px-3 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50/30 to-indigo-50/30">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ $initial }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-900 truncate">
                                                {{ auth()->user()->name ?? '' }}</p>
                                            <p class="text-[10px] text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-phone text-blue-500 text-[9px]"></i>
                                                {{ auth()->user()->whatsapp_number ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-1.5">
                                    <a href="{{ route('riwayat') }}"
                                        class="flex items-center gap-2.5 px-3 py-2.5 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
                                        <i
                                            class="fas fa-history text-blue-500 text-sm group-hover:scale-110 transition-transform"></i>
                                        <div class="flex-1">
                                            <span
                                                class="text-sm font-medium text-gray-700 group-hover:text-blue-600">Riwayat</span>
                                            <p class="text-[10px] text-gray-500">Lihat booking Anda</p>
                                        </div>
                                    </a>
                                    <button type="button" id="logout-btn"
                                        class="w-full text-left flex items-center gap-2.5 px-3 py-2.5 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-200 group border-t border-gray-100 mt-1">
                                        <i
                                            class="fas fa-sign-out-alt text-red-500 text-sm group-hover:scale-110 transition-transform"></i>
                                        <div class="flex-1">
                                            <span
                                                class="text-sm font-medium text-gray-700 group-hover:text-red-600">Logout</span>
                                            <p class="text-[10px] text-gray-500">Keluar dari akun</p>
                                        </div>
                                    </button>
                                </div>
                            </div>


                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>

                        </div>
                    @endguest
                </div>
            </div>

            <div id="menu"
                class="hidden flex-col bg-white/95 backdrop-blur-lg md:hidden px-4 py-6 space-y-2 border-t border-gray-200 shadow-xl">
                <a href="{{ route('home') }}"
                    class="block py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 font-medium {{ request()->routeIs('home') ? 'bg-gradient-to-r from-blue-500/10 to-indigo-500/10 text-blue-600 border-l-4 border-blue-500' : 'text-gray-700' }}">
                    <i class="fas fa-home w-5"></i> Home
                </a>
                <a href="{{ route('jadwal') }}"
                    class="block py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 font-medium {{ request()->routeIs('jadwal') ? 'bg-gradient-to-r from-blue-500/10 to-indigo-500/10 text-blue-600 border-l-4 border-blue-500' : 'text-gray-700' }}">
                    <i class="fas fa-calendar-alt w-5"></i> Jadwal Keberangkatan
                </a>
                <a href="{{ auth()->check() ? route('pesan') : route('login') }}"
                    class="block py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 font-medium {{ request()->routeIs('pesan') ? 'bg-gradient-to-r from-blue-500/10 to-indigo-500/10 text-blue-600 border-l-4 border-blue-500' : 'text-gray-700' }}">
                    <i class="fas fa-ticket-alt w-5"></i> Pesan Tiket
                </a>
                <a href="{{ auth()->check() ? route('riwayat') : route('login') }}"
                    class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 font-medium {{ request()->routeIs('riwayat') ? 'bg-gradient-to-r from-blue-500/10 to-indigo-500/10 text-blue-600 border-l-4 border-blue-500' : 'text-gray-700' }}">
                    <span><i class="fas fa-history w-5"></i> Riwayat Transaksi</span>
                    @auth
                        @php
                            $userPendingCount = \App\Models\Booking::where('user_id', auth()->id())
                                ->where('status', 'pending')
                                ->count();
                        @endphp

                        @if ($userPendingCount > 0)
                            <span
                                class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-2.5 py-1 rounded-full min-w-6 text-center shadow-lg animate-pulse">
                                {{ $userPendingCount }}
                            </span>
                        @endif

                    @endauth
                </a>
                <div class="pt-4 border-t border-gray-200 mt-4">
                    @guest
                        <a href="{{ route('login') }}"
                            class="block px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 text-center font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                    @else
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-3">
                            <div class="flex items-center gap-3">
                                @php
                                    $initial = strtoupper(substr(auth()->user()->name, 0, 1));
                                @endphp
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md">
                                    {{ $initial }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500">Logged in as</p>
                                    <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="logout-btn-mobile"
                            class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>

                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>

                    @endguest
                </div>
            </div>
        </nav>
        <main class="min-h-screen pt-20 md:pt-24">
            <div class="w-full">
                <div class="max-w-7xl mx-auto px-6">
                    @yield('content')
                </div>
            </div>
        </main>
    @endif

    @hasSection('footer')
        @yield('footer')
    @endif

    @stack('scripts')
</body>

</html>
