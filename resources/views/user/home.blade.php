@extends('layouts.app')

@section('content')
    <!-- Hero Section with Gradient Background - Full Bleed -->
    <div class="relative w-screen -ml-[50vw] left-1/2 overflow-hidden -mt-4 md:-mt-6">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50"></div>

        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 pt-12 pb-16 md:pt-16 md:pb-24">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Left side: Text Content -->
                <div class="lg:w-1/2 space-y-8 animate-fade-in-right">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full shadow-lg border border-blue-100">
                        <span class="flex h-3 w-3 relative">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        <span class="text-sm font-semibold text-gray-700">Travel Terpercaya Sejak 2014</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        <span
                            class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Travel Nyaman,<br>Aman & Terpercaya
                        </span>
                    </h1>

                    <!-- Subheading -->
                    <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                        Pesan tiket travel dengan mudah dan cepat. Nikmati perjalanan Anda dengan armada modern dan supir
                        berpengalaman.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('jadwal') }}"
                            class="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 font-semibold">
                            <span>Lihat Jadwal</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Right side: Illustration -->
                <div class="lg:w-1/2 animate-fade-in-left">
                    <div class="relative pt-8">
                        <!-- Animated Sky Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/25 via-indigo-400/15 to-purple-400/25 rounded-3xl blur-3xl animate-pulse"
                            style="animation-duration: 4s;">
                        </div>

                        <!-- Dynamic Glow Ring -->
                        <div
                            class="absolute -inset-6 bg-gradient-to-br from-blue-500/15 via-indigo-500/10 to-purple-500/15 rounded-3xl blur-2xl">
                        </div>

                        <!-- Cloud Animation Elements (Decorative) -->
                        <div class="absolute top-10 right-10 w-20 h-8 bg-white/20 rounded-full blur-xl animate-blob"></div>
                        <div
                            class="absolute top-20 right-32 w-16 h-6 bg-white/15 rounded-full blur-lg animate-blob animation-delay-2000">
                        </div>

                        <!-- Image Container with Shadow Enhancement -->
                        <div class="relative flex items-end justify-center" style="transform: translateY(12px);">
                            <!-- Ground Shadow -->
                            <div
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4/5 h-8 bg-gradient-to-t from-blue-900/20 to-transparent rounded-full blur-2xl">
                            </div>

                            <img src="{{ asset('asset/home.webp') }}" alt="PT. Pelita Tran Prima Travel Service"
                                class="w-full drop-shadow-2xl object-contain relative z-10"
                                style="filter: saturate(1.1) brightness(1.05);" loading="eager" fetchpriority="high"
                                width="800" height="600">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section - Full Bleed -->
    <section class="relative w-screen -ml-[50vw] left-1/2 py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-up animate-on-scroll">
                <span class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
                    Layanan Terbaik
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Kenapa Memilih <span
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Pelita Tran
                        Prima?</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kami berkomitmen memberikan pengalaman perjalanan terbaik dengan layanan berkualitas tinggi
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service Card 1 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:-translate-y-2">
                        <!-- Icon Container -->
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-bus-alt text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            Armada Modern
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Minibus dengan fasilitas lengkap: AC sejuk, kursi reclining yang nyaman, audio entertainment,
                            dan interior bersih untuk perjalanan yang menyenangkan
                        </p>

                        <!-- Hover Effect Arrow -->
                        <div
                            class="mt-4 flex items-center text-blue-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Service Card 2 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-indigo-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-shield-alt text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                            Keamanan Terjamin
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Supir profesional dan berpengalaman untuk kenyamanan perjalanan Anda dengan standar
                            keamanan tinggi.
                        </p>

                        <div
                            class="mt-4 flex items-center text-indigo-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Service Card 3 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-purple-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Tepat Waktu
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Kami berkomitmen untuk berangkat dan tiba sesuai jadwal yang telah ditentukan agar Anda dapat
                            merencanakan perjalanan dengan baik
                        </p>

                        <div
                            class="mt-4 flex items-center text-purple-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Service Card 4 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-ticket-alt text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            Pemesanan Mudah
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Pesan tiket kapan saja melalui website kami dengan proses yang cepat dan mudah dipahami
                        </p>

                        <div
                            class="mt-4 flex items-center text-blue-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Service Card 5 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-indigo-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-headset text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                            Layanan Pelanggan
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Tim kami siap membantu menjawab pertanyaan dan keluhan Anda melalui WhatsApp, telepon, atau
                            email
                        </p>

                        <div
                            class="mt-4 flex items-center text-indigo-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>

                <!-- Service Card 6 -->
                <div class="group fade-up animate-on-scroll">
                    <div
                        class="relative h-full p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-purple-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                            </div>
                            <div
                                class="relative w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-tags text-2xl text-white"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                            Harga Terjangkau
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Harga yang bersaing dengan kualitas pelayanan terbaik, transaksi transparan tanpa biaya
                            tersembunyi
                        </p>

                        <div
                            class="mt-4 flex items-center text-purple-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm">Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Location Section - Full Bleed -->
    <section class="relative w-screen -ml-[50vw] left-1/2 py-20 bg-white" id="contact">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-up animate-on-scroll">
                <span class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
                    Hubungi Kami
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Siap Melayani <span
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Perjalanan
                        Anda</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Tim kami siap membantu Anda 24/7. Hubungi kami untuk informasi lebih lanjut
                </p>
            </div>

            <div class="grid lg:grid-cols-5 gap-12 items-start">
                <!-- Contact Info -->
                <div class="lg:col-span-2 space-y-6 fade-right animate-on-scroll">
                    <!-- Contact Card 1 -->
                    <div
                        class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div
                                class="shrink-0 w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-map-marker-alt text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">Alamat Kantor</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Jl. Raya Pekanbaru–Bangkinang, Rimba Panjang, Tambang, Kampar, Riau 28293
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card 2 -->
                    <div
                        class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div
                                class="shrink-0 w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-phone-alt text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">Telepon</h4>
                                <a href="tel:+62761123456"
                                    class="text-gray-600 text-sm hover:text-green-600 transition-colors">
                                    +62 761 123456
                                </a>
                                <p class="text-xs text-gray-500 mt-1">Senin - Minggu, 06:00 - 22:00 WIB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card 3 -->
                    <div
                        class="group p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div
                                class="shrink-0 w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">Email</h4>
                                <a href="mailto:info@pelitatranprima.com"
                                    class="text-gray-600 text-sm hover:text-purple-600 transition-colors">
                                    info@pelitatranprima.com
                                </a>
                                <p class="text-xs text-gray-500 mt-1">Respons dalam 1x24 jam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card 4 -->
                    <div
                        class="group p-6 bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-start gap-4">
                            <div
                                class="shrink-0 w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-xl text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">WhatsApp</h4>
                                <a href="https://wa.me/6281234567890" target="_blank"
                                    class="text-gray-600 text-sm hover:text-orange-600 transition-colors">
                                    +62 812 3456 7890
                                </a>
                                <p class="text-xs text-gray-500 mt-1">Chat langsung dengan CS kami</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="lg:col-span-3 fade-left animate-on-scroll">
                    <div class="sticky top-24">
                        <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-200">
                            <div class="map-container"
                                data-map-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.692873287629!2d101.3479711!3d0.454352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5a9de27931077%3A0xcd486083af3b9367!2sPelita%20Transport!5e0!3m2!1sen!2sid!4v1756371798177!5m2!1sen!2sid">
                                <div
                                    class="map-placeholder bg-gradient-to-br from-gray-100 to-gray-200 h-[500px] flex items-center justify-center cursor-pointer hover:from-gray-200 hover:to-gray-300 transition-all">
                                    <div class="text-center">
                                        <div
                                            class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                            <i class="fas fa-map-location-dot text-4xl text-blue-600"></i>
                                        </div>
                                        <p class="text-gray-700 font-semibold mb-2">Klik untuk memuat peta</p>
                                        <p class="text-gray-500 text-sm">Lihat lokasi kantor kami di Google Maps</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="https://www.google.com/maps/place/Pelita+Transport/@0.454352,101.3479711,17z/data=!3m1!4b1!4m6!3m5!1s0x31d5a9de27931077:0xcd486083af3b9367!8m2!3d0.454352!4d101.350546!16s%2Fg%2F11c5z6xjg7?entry=ttu"
                                target="_blank"
                                class="w-full group flex items-center justify-center gap-3 bg-blue-600 text-white py-4 px-6 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl hover:scale-105">
                                <i class="fas fa-directions text-lg group-hover:rotate-12 transition-transform"></i>
                                <span>Buka di Maps</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats - Full Bleed -->
    <section
        class="relative w-screen -ml-[50vw] left-1/2 py-20 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 text-white overflow-hidden"
        id="stats-section">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-12 fade-up animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Dipercaya Ribuan Pelanggan</h2>
                <p class="text-blue-100 text-lg">Statistik yang membuktikan komitmen kami</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- Stat 1 -->
                <div class="fade-up animate-on-scroll">
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-route text-2xl"></i>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="500">0</div>
                            <p class="text-blue-100 text-sm md:text-base">Perjalanan per Bulan</p>
                        </div>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="fade-up animate-on-scroll">
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-bus text-2xl"></i>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="50">0</div>
                            <p class="text-blue-100 text-sm md:text-base">Armada Modern</p>
                        </div>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="fade-up animate-on-scroll">
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-map-marked-alt text-2xl"></i>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="25">0</div>
                            <p class="text-blue-100 text-sm md:text-base">Rute Tujuan</p>
                        </div>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="fade-up animate-on-scroll">
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-6 md:p-8 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                            <div class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="99" data-suffix="%">0%
                            </div>
                            <p class="text-blue-100 text-sm md:text-base">Kepuasan Pelanggan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Footer Section - Full Width --}}
@section('footer')
    <footer
        class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 text-white py-16 w-full overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                {{-- Company Info --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-bus text-white text-xl"></i>
                        </div>
                        <div>
                            <h3
                                class="text-xl font-bold bg-gradient-to-r from-blue-200 to-indigo-200 bg-clip-text text-transparent">
                                PT. PELITA</h3>
                            <p class="text-xs text-blue-200">TRAN PRIMA</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        Melayani perjalanan Anda dengan aman, nyaman, dan terpercaya sejak 2014.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#"
                            class="group w-10 h-10 rounded-lg bg-white/10 backdrop-blur-sm hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-facebook-f text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                        <a href="#"
                            class="group w-10 h-10 rounded-lg bg-white/10 backdrop-blur-sm hover:bg-gradient-to-r hover:from-pink-500 hover:to-purple-600 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-instagram text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                        <a href="#"
                            class="group w-10 h-10 rounded-lg bg-white/10 backdrop-blur-sm hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-whatsapp text-sm group-hover:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>

                {{-- Layanan --}}
                <div>
                    <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                        Layanan
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Travel Antar Kota
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Charter Bus
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Pariwisata
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Kargo
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Rute Populer --}}
                <div>
                    <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                        Rute Populer
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Pekanbaru - Padang
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Pekanbaru - Rohul
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Pekanbaru - Medan
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-white hover:translate-x-2 inline-flex items-center gap-2 transition-all duration-300 group">
                                <i class="fas fa-chevron-right text-xs text-blue-400 group-hover:text-blue-300"></i>
                                Pekanbaru - Rohil
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kontak Cepat --}}
                <div>
                    <h4 class="text-lg font-bold mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                        Kontak Cepat
                    </h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-gray-300 group hover:text-white transition-colors">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500/30 transition-colors">
                                <i class="fas fa-phone-alt text-blue-400 text-sm"></i>
                            </div>
                            <span class="pt-1">+62 761 123456</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-300 group hover:text-white transition-colors">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-500/30 transition-colors">
                                <i class="fas fa-envelope text-blue-400 text-sm"></i>
                            </div>
                            <span class="pt-1">info@pelitatranprima.com</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-300 group hover:text-white transition-colors">
                            <div
                                class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-green-500/30 transition-colors">
                                <i class="fab fa-whatsapp text-green-400 text-sm"></i>
                            </div>
                            <span class="pt-1">+62 812 3456 7890</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-white/10 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-300 text-sm">
                        &copy; 2024 <span class="font-semibold text-white">PT. Pelita Tran Prima</span>. Semua hak
                        dilindungi.
                    </p>
                    <div class="flex items-center gap-6 text-sm">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">Kebijakan Privasi</a>
                        <span class="text-gray-600">•</span>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Decorative Gradient Orbs --}}
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>
    </footer>
@endsection
