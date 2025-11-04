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
            padding: 15px;
            background: #ffffff;
            font-size: 14px;
            color: #1e293b;
        }

        .ticket-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 50%, #8b5cf6 100%);
            color: #ffffff;
            text-align: center;
            padding: 18px 20px;
        }

        .ticket-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .ticket-number {
            font-size: 14px;
            font-weight: 600;
            color: #e0e7ff;
            margin-top: 6px;
            letter-spacing: 0.3px;
        }

        .ticket-body {
            padding: 20px;
        }

        .info-line {
            font-size: 13px;
            color: #475569;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-line:last-child {
            border-bottom: none;
        }

        .info-line strong {
            color: #334155;
            font-weight: 600;
            min-width: 160px;
            flex-shrink: 0;
        }

        .info-line span {
            flex: 1;
            text-align: right;
            color: #1e293b;
        }

        .ticket-divider {
            height: 1px;
            background: repeating-linear-gradient(to right,
                    #cbd5e1 0px,
                    #cbd5e1 8px,
                    transparent 8px,
                    transparent 12px);
            margin: 15px 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.2px;
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

        .qr-code {
            text-align: center;
            margin: 12px 0 8px;
        }

        .qr-box {
            border: 2px solid #cbd5e1;
            padding: 12px;
            border-radius: 8px;
            display: inline-block;
            background: #f8fafc;
        }

        .qr-box .ticket-number {
            color: #1e293b;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .qr-box small {
            display: block;
            color: #64748b;
            margin-top: 3px;
            font-size: 10px;
            font-weight: 500;
        }

        .note {
            text-align: center;
            color: #64748b;
            font-size: 11px;
            margin-top: 8px;
            line-height: 1.5;
        }

        .note p {
            margin: 4px 0;
        }

        .note strong {
            color: #1e293b;
            font-weight: 600;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .ticket-container {
                box-shadow: none;
                border: 1px solid #e2e8f0;
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

            <div class="info-line">
                <strong>Nama Penumpang:</strong>
                <span>{{ $booking->user->name }}</span>
            </div>
            <div class="info-line">
                <strong>Nomor Kursi:</strong>
                <span>{{ $booking->seat_number }}</span>
            </div>
            <div class="info-line">
                <strong>Rute:</strong>
                <span>{{ $booking->jadwal->rute->kota_asal }} - {{ $booking->jadwal->rute->kota_tujuan }}</span>
            </div>
            <div class="info-line">
                <strong>Keberangkatan:</strong>
                <span>{{ \Carbon\Carbon::parse($booking->jadwal->tanggal)->format('d M Y') }},
                    {{ $booking->jadwal->jam }}</span>
            </div>
            <div class="info-line">
                <strong>Mobil:</strong>
                <span>{{ $booking->jadwal->mobil->merk }} ({{ $booking->jadwal->mobil->nomor_polisi }})</span>
            </div>
            <div class="info-line">
                <strong>Supir:</strong>
                <span>{{ $booking->jadwal->mobil->supir->nama ?? 'N/A' }}</span>
            </div>
            <div class="info-line">
                <strong>Harga:</strong>
                <span>Rp {{ number_format($booking->jadwal->harga, 0, ',', '.') }}</span>
            </div>
            <div class="ticket-divider"></div>

            <div class="info-line">
                <strong>Status Booking:</strong>
                <span
                    class="status-badge {{ $booking->status == 'setuju' ? 'status-approved' : ($booking->status == 'batal' ? 'status-cancelled' : 'status-pending') }}">

                    @if ($booking->status == 'setuju')
                        <svg style="width: 10px; height: 10px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    @elseif($booking->status == 'pending')
                        <svg style="width: 10px; height: 10px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg style="width: 10px; height: 10px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif

                    {{ ucfirst($booking->status) }}
                </span>
            </div>

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
                <p><strong>Perhatian:</strong> Tunjukkan e-ticket ini saat naik kendaraan. Tiket dicetak pada
                    {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</body>

</html>
