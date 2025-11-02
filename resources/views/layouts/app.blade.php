<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>PT. PELITA TRAN PRIMA</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Preload critical assets for better LCP --}}
    @if (request()->is('/'))
        <link rel="preload" as="image" href="{{ asset('asset/home.png') }}" fetchpriority="high">
    @endif
    <link rel="preload" as="image" href="{{ asset('asset/logo.png') }}">

    @if (app()->environment() !== 'testing')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    @if (auth()->check() && auth()->user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    @endif
</head>

<body class="bg-gray-100 m-0 p-0">

    @if (session('success'))
        <div data-success-message="{{ session('success') }}" style="display: none;"></div>
    @endif

    @if (session('error'))
        <div data-error-message="{{ session('error') }}" style="display: none;"></div>
    @endif

    @if (auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Layout -->
        <div class="flex min-h-screen">
            <!-- Hamburger Button -->
            <button id="menu-toggle" class="md:hidden p-4 focus:outline-none" aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Sidebar -->
            <aside id="sidebar"
                class="w-64 min-h-screen bg-blue-900 text-white flex flex-col transform -translate-x-full
                       md:translate-x-0 transition-transform duration-300 md:static fixed z-50">
                <div class="p-4 text-xl font-bold border-b border-blue-700 flex items-center gap-2">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover"
                        width="40" height="40">
                    PT. PELITA TRAN PRIMA
                </div>

                <nav class="flex-1 p-4 space-y-2">
                    {{-- contoh link --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.bookings') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.bookings') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-ticket"></i> Data Pemesanan
                    </a>
                    <a href="{{ route('admin.jadwals') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.jadwals') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-calendar"></i> Penjadwalan
                    </a>
                    <a href="{{ route('admin.pelanggan') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.pelanggan') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-users"></i> Data Pelanggan
                    </a>
                    <a href="{{ route('admin.laporan') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.laporan') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-file-invoice-dollar"></i> Laporan Pendapatan
                    </a>
                    <a href="{{ route('admin.rute') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.rute') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-road"></i> Data Rute
                    </a>
                    <a href="{{ route('admin.mobil') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.mobil') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-bus"></i> Data Mobil
                    </a>
                    <a href="{{ route('admin.supir') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.supir') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-user"></i> Data Supir
                    </a>
                    <a href="{{ route('admin.pembayaran') }}"
                        class="flex items-center gap-2 py-2 px-3 rounded hover:bg-blue-700
                              {{ request()->routeIs('admin.pembayaran') ? 'bg-blue-800' : '' }}">
                        <i class="fa fa-money-bill-wave"></i> Kelola Pembayaran
                    </a>
                </nav>

                <div class="p-4 border-t border-blue-700">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button id="logout-btn" type="button"
                            class="w-full flex items-center justify-center gap-2 bg-red-600 py-2 rounded hover:bg-red-700">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    @else
        <!-- User Layout -->
        <nav id="navbar"
            class="bg-[#062B72] text-white shadow-md fixed top-0 left-0 w-full z-50 transition-all duration-300">
            <div class="container mx-auto flex justify-between items-center px-4 py-3 md:px-6">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-lg md:text-xl font-bold">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-cover"
                        width="40" height="40">
                    <span>PT. PELITA TRAN PRIMA</span>
                </a>

                {{-- Hamburger mobile --}}
                <button id="menu-toggle" class="md:hidden focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>

                {{-- Desktop menu --}}
                <div class="hidden md:flex md:items-center gap-6">
                    <a href="{{ route('home') }}" class="relative group">
                        Home
                        <span
                            class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('jadwal') }}" class="relative group">
                        Melihat Jadwal
                        <span
                            class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                    </a>
                    <a href="{{ auth()->check() ? route('pesan') : route('login') }}" class="relative group">
                        Pesan Tiket
                        <span
                            class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                    </a>
                    <a href="{{ auth()->check() ? route('riwayat') : route('login') }}" class="relative group">
                        Riwayat Transaksi
                        <span
                            class="absolute left-0 -bottom-1 w-0 h-[2px] bg-white transition-all group-hover:w-full"></span>
                    </a>

                    @guest
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 bg-[#FF6B2C] rounded hover:bg-[#E55A1F] transition">
                            Login
                        </a>
                    @else
                        @php
                            $firstName = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
                        @endphp

                        <div class="relative flex items-center gap-2">
                            <span id="greeting">Selamat Malam</span>,
                            <strong>{{ $firstName }}</strong>

                            {{-- Icon dropdown --}}
                            <span id="greeting-icon" class="ml-1 cursor-pointer">🌙</span>

                            {{-- Dropdown --}}
                            <div id="user-dropdown"
                                class="hidden absolute right-0 top-10 mt-1 w-40 bg-white text-gray-700 rounded shadow-lg py-2 z-50 transition-opacity duration-200 opacity-0">
                                <button type="button" id="logout-btn"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                    Logout
                                </button>
                            </div>

                            {{-- Hidden form --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    @endguest
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="menu" class="hidden flex-col bg-[#062B72] md:hidden px-6 py-4 space-y-2">
                <a href="{{ route('home') }}" class="block hover:text-blue-300">Home</a>
                <a href="{{ route('jadwal') }}" class="block hover:text-blue-300">Jadwal Keberangkatan</a>
                <a href="{{ auth()->check() ? route('pesan') : route('login') }}"
                    class="block hover:text-blue-300">Pesan
                    Tiket</a>
                <a href="{{ auth()->check() ? route('riwayat') : route('login') }}"
                    class="block hover:text-blue-300">Riwayat Transaksi</a>

                @guest
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 bg-[#FF6B2C] rounded hover:bg-[#E55A1F] transition">
                        Login
                    </a>
                @else
                    <span class="block">Halo, {{ auth()->user()->name }}</span>
                    <button type="button" id="logout-btn-mobile"
                        class="w-full px-4 py-2 bg-red-500 rounded hover:bg-red-600 transition">
                        Logout
                    </button>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endguest
            </div>
        </nav>

        <main class="min-h-screen pt-16">
            <div class="container mx-auto p-6">
                @yield('content')
            </div>
        </main>
    @endif

    @hasSection('footer')
        @yield('footer')
    @endif

    @stack('scripts')
</body>

</html>
