<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Booking Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all booking-related configuration options.
    |
    */

    /*
    * Waktu expired untuk pending bookings (dalam menit)
    * Default: 30 menit
    */
    'pending_expiry_minutes' => env('BOOKING_PENDING_EXPIRY', 30),

    /*
    * Batas waktu pemesanan sebelum keberangkatan (dalam jam)
    * Default: 1 jam sebelum keberangkatan
    */
    'booking_close_hours' => env('BOOKING_CLOSE_HOURS', 1),

    /*
    * Batas waktu pembatalan booking sebelum keberangkatan (dalam jam)
    * Default: 2 jam sebelum keberangkatan
    */
    'cancel_close_hours' => env('BOOKING_CANCEL_HOURS', 2),

    /*
    * Maksimal jumlah kursi per booking
    * Default: 7 kursi
    */
    'max_seats_per_booking' => env('MAX_SEATS_PER_BOOKING', 7),

    /*
    * Waktu expired OTP (dalam menit)
    * Default: 10 menit
    */
    'otp_expiry_minutes' => env('OTP_EXPIRY_MINUTES', 10),

];
