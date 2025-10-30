@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2" data-aos="fade-down">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
        </svg>
        Kelola Pembayaran
    </h2>

    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow" data-aos="fade-up">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-6 py-3 text-center border border-white">Ticket Number</th>
                    <th class="px-6 py-3 text-center border border-white">Pelanggan</th>
                    <th class="px-6 py-3 text-center border border-white">Rute</th>
                    <th class="px-6 py-3 text-center border border-white">Tanggal</th>
                    <th class="px-6 py-3 text-center border border-white">Jam</th>
                    <th class="px-6 py-3 text-center border border-white">Kursi</th>
                    <th class="px-6 py-3 text-center border border-white">Status Booking</th>
                    <th class="px-6 py-3 text-center border border-white">Status Pembayaran</th>
                    <th class="px-6 py-3 text-center border border-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->ticket_number ?? 'N/A' }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->user->name }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ $booking->jadwal->rute->kota_asal }} - {{ $booking->jadwal->rute->kota_tujuan }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">
                            {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->jadwal->jam }}</td>
                        <td class="px-6 py-3 text-center border border-white">{{ $booking->seat_number }}</td>
                        <td class="px-6 py-3 text-center border border-white">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->status == 'setuju'
                                    ? 'bg-green-100 text-green-700'
                                    : ($booking->status == 'batal'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center border border-white">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $booking->payment_status == 'sudah_bayar' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $booking->payment_status == 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-white">
                            <form action="{{ route('admin.pembayaran.update', $booking) }}" method="POST"
                                class="flex items-center justify-center gap-2">
                                @csrf @method('PUT')
                                <input type="hidden" name="payment_status" id="payment_status_{{ $booking->id }}"
                                    value="">
                                @if ($booking->payment_status !== 'sudah_bayar')
                                    <button type="submit"
                                        onclick="document.getElementById('payment_status_{{ $booking->id }}').value='sudah_bayar'"
                                        class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 transition">
                                        Sudah Bayar
                                    </button>
                                @else
                                    <a href="{{ route('booking.download.ticket', $booking) }}"
                                        class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-3 text-center text-gray-500 border border-white">
                            Tidak ada data pembayaran ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center" data-aos="fade-up" data-aos-delay="400">
        {{ $bookings->links() }}
    </div>
@endsection
