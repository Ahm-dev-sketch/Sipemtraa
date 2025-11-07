@if ($paginator->hasPages())
    @php
        $from = $paginator->firstItem() ?? 0;
        $to = $paginator->lastItem() ?? 0;
        $total = $paginator->total();
        $last = $paginator->lastPage();
        $current = $paginator->currentPage();
        $window = 3;
    @endphp

    <!-- NAVIGASI PAGINATION - Komponen navigasi halaman dengan tampilan mobile dan desktop -->
    <nav role="navigation" aria-label="Navigasi Halaman" class="w-full">

        {{-- TAMPILAN MOBILE - Navigasi sederhana dengan prev/current/next dan ringkasan --}}
        <div class="flex items-center justify-between sm:hidden px-2">
            {{-- Prev --}}
            <div>
                @if ($paginator->onFirstPage())
                    <span aria-hidden="true"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white text-gray-300 border border-gray-100">&lt;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">&lt;</a>
                @endif
            </div>

            {{-- Current / total --}}
            <div class="text-sm text-gray-700">Halaman <span class="font-semibold">{{ $current }}</span> dari <span
                    class="font-medium">{{ $last }}</span></div>

            {{-- Next --}}
            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">&gt;</a>
                @else
                    <span aria-hidden="true"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white text-gray-300 border border-gray-100">&gt;</span>
                @endif
            </div>
        </div>

        {{-- TAMPILAN DESKTOP/TABLET - Navigasi lengkap dengan angka dan ellipsis --}}
        <div class="hidden sm:flex items-center justify-between">
            {{-- RINGKASAN KIRI - Menampilkan informasi jumlah item yang ditampilkan --}}
            <div class="text-sm text-gray-600 mr-4 hidden md:block">Menampilkan
                {{ $from }}&ndash;{{ $to }} dari {{ $total }}</div>

            <div class="flex items-center">
                {{-- TOMBOL PREVIOUS - Navigasi ke halaman sebelumnya --}}
                <div class="mr-3">
                    @if ($paginator->onFirstPage())
                        <span aria-hidden="true"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white text-gray-300 border border-gray-100"
                            aria-label="Tidak ada halaman sebelumnya">&lt;</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white shadow-sm">&lt;</a>
                    @endif
                </div>

                {{-- TOMBOL ANGKA - Daftar nomor halaman dengan logika ellipsis --}}
                <div class="flex items-center space-x-2" aria-hidden="false">

                    @if ($last <= $window)
                        @for ($i = 1; $i <= $last; $i++)
                            @if ($i == $current)
                                <span aria-current="page"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md border-0 text-sm font-semibold bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white shadow-sm">{{ $i }}</span>
                            @else
                                <a href="{{ $paginator->url($i) }}" aria-label="Halaman {{ $i }}"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 bg-white text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-200">{{ $i }}</a>
                            @endif
                        @endfor
                    @else
                        @if ($current <= 2)
                            @for ($i = 1; $i <= min($window, $last); $i++)
                                @if ($i == $current)
                                    <span aria-current="page"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border-0 text-sm font-semibold bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white shadow-sm">{{ $i }}</span>
                                @else
                                    <a href="{{ $paginator->url($i) }}" aria-label="Halaman {{ $i }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-blue-100 bg-blue-50 text-sm text-blue-700 hover:bg-blue-100">{{ $i }}</a>
                                @endif
                            @endfor
                            @if ($last > $window)
                                <span class="text-gray-400 px-2">…</span>
                                <a href="{{ $paginator->url($last) }}" aria-label="Halaman terakhir"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-sm text-gray-600 hover:bg-gray-50">{{ $last }}</a>
                            @endif
                        @elseif ($current >= $last - 1)
                            <a href="{{ $paginator->url(1) }}" aria-label="Halaman 1"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-sm text-gray-600 hover:bg-gray-50">1</a>
                            <span class="text-gray-400 px-2">…</span>
                            @for ($i = max(1, $last - $window + 1); $i <= $last; $i++)
                                @if ($i == $current)
                                    <span aria-current="page"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border-0 text-sm font-semibold bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white shadow-sm">{{ $i }}</span>
                                @else
                                    <a href="{{ $paginator->url($i) }}" aria-label="Halaman {{ $i }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-blue-100 bg-blue-50 text-sm text-blue-700 hover:bg-blue-100">{{ $i }}</a>
                                @endif
                            @endfor
                        @else
                            <a href="{{ $paginator->url(1) }}" aria-label="Halaman 1"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-sm text-gray-600 hover:bg-gray-50">1</a>
                            <span class="text-gray-400 px-2">…</span>

                            @for ($i = $current - 1; $i <= $current + 1; $i++)
                                @if ($i == $current)
                                    <span aria-current="page"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border-0 text-sm font-semibold bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 text-white shadow-sm">{{ $i }}</span>
                                @else
                                    <a href="{{ $paginator->url($i) }}" aria-label="Halaman {{ $i }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-md border border-blue-100 bg-blue-50 text-sm text-blue-700 hover:bg-blue-100">{{ $i }}</a>
                                @endif
                            @endfor

                            <span class="text-gray-400 px-2">…</span>
                            <a href="{{ $paginator->url($last) }}" aria-label="Halaman terakhir"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-sm text-gray-600 hover:bg-gray-50">{{ $last }}</a>
                        @endif
                    @endif
                </div>

                {{-- TOMBOL NEXT - Navigasi ke halaman berikutnya --}}
                <div class="ml-3">
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-gray-600 hover:shadow-sm">&gt;</a>
                    @else
                        <span aria-hidden="true"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md border bg-white text-gray-400">&gt;</span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
