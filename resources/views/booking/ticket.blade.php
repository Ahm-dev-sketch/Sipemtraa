<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $booking->ticket_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #eef2f7;
            font-size: 20px;
            color: #111827;
        }

        .ticket-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .ticket-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            text-align: center;
            padding: 25px 20px;
        }

        .ticket-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .ticket-number {
            font-size: 20px;
            font-weight: 600;
            color: #e0e7ff;
            margin-top: 6px;
        }

        .ticket-body {
            padding: 30px 40px;
        }

        .info-line {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .info-line strong {
            color: #374151;
            font-weight: 600;
        }

        .ticket-divider {
            border-top: 2px dashed #d1d5db;
            margin: 25px 0;
        }

        /* ======== STATUS SECTION ======== */
        .status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .status-item {
            width: 50%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
        }

        .status-item strong {
            color: #374151;
            font-weight: 600;
            margin-right: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 17px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            text-align: center;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-paid {
            background: #cffafe;
            color: #155e75;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ======== QR SECTION ======== */
        .qr-code {
            text-align: center;
            margin: 25px 0;
        }

        .qr-box {
            border: 2px solid #4b5563;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            background: #f9fafb;
        }

        .qr-box .ticket-number {
            color: #111827;
            font-size: 20px;
            font-weight: 600;
        }

        .qr-box small {
            display: block;
            color: #6b7280;
            margin-top: 5px;
            font-size: 19px;
        }

        .note {
            text-align: center;
            color: #6b7280;
            font-size: 20px;
            margin-top: 15px;
        }

        .note strong {
            color: #111827;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>E-Ticket Travel</h1>
            <div class="ticket-number">No. Tiket: {{ $booking->ticket_number }}</div>
        </div>

        <div class="ticket-body">
            {{-- Informasi Penumpang --}}
            <div class="info-line"><strong>Nama Penumpang:</strong> {{ $booking->user->name }}</div>
            <div class="info-line"><strong>Nomor Kursi:</strong> {{ $booking->seat_number }}</div>
            <div class="info-line"><strong>Rute Perjalanan:</strong> {{ $booking->jadwal->rute->kota_asal }} -
                {{ $booking->jadwal->rute->kota_tujuan }}</div>
            <div class="info-line"><strong>Tanggal Keberangkatan:</strong>
                {{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }}</div>
            <div class="info-line"><strong>Jam Keberangkatan:</strong> {{ $booking->jadwal->jam }}</div>
            <div class="info-line"><strong>Mobil:</strong> {{ $booking->jadwal->mobil->merk }} -
                {{ $booking->jadwal->mobil->nomor_polisi }}</div>
            <div class="info-line"><strong>Supir:</strong> {{ $booking->jadwal->mobil->supir->nama ?? 'N/A' }}</div>
            <div class="info-line"><strong>Harga Tiket:</strong> Rp
                {{ number_format($booking->jadwal->harga, 0, ',', '.') }}</div>

            <div class="ticket-divider"></div>

            {{-- Status Booking --}}
            <div class="info-line">
                <strong>Status Booking:</strong>
                <span class="status-badge {{ $booking->status == 'setuju' ? 'status-approved' : ($booking->status == 'batal' ? 'status-cancelled' : 'status-pending') }}" style="display: inline-flex; align-items: center; gap: 4px;">
                    @if($booking->status == 'setuju')
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif($booking->status == 'pending')
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            {{-- Status Pembayaran --}}
            <div class="info-line">
                <strong>Status Pembayaran:</strong>
                <span
                    class="status-badge {{ $booking->payment_status == 'sudah_bayar' ? 'status-paid' : 'status-unpaid' }}">
                    {{ $booking->payment_status == 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                </span>
            </div>

            <div class="qr-code">
                <div class="qr-box">
                    <div class="ticket-number">{{ $booking->ticket_number }}</div>
                    <small>Nomor Tiket</small>
                </div>
            </div>

            <div class="ticket-divider"></div>

            <div class="note">
                <p><strong>Perhatian:</strong> Tunjukkan e-ticket ini saat naik kendaraan.</p>
                <p>E-ticket ini dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</body>

</html>