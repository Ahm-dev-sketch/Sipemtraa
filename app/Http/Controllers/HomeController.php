<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk halaman home pengguna
 * Menampilkan halaman utama dengan data pengguna yang sedang login
 */
class HomeController extends Controller
{
    // Menampilkan halaman home dengan nama depan pengguna
    public function index(Request $request)
    {
        $user = Auth::user();
        // Ambil nama depan dari nama lengkap pengguna
        $firstName = $user ? \Illuminate\Support\Str::before($user->name, ' ') : '';

        return view('user.home', ['firstName' => $firstName]);
    }
}
