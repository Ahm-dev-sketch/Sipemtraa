<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Controller untuk mengelola autentikasi pengguna
 * Menangani login, registrasi, verifikasi OTP, dan reset password
 */
class AuthController extends Controller
{
    // Dependency injection untuk OtpService
    protected $otpService;

    // Constructor untuk inject OtpService
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login pengguna
    public function login(Request $request)
    {
        $credentials = $request->only('whatsapp_number', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $redirect = $request->input('redirect');

            // Redirect admin ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Redirect ke URL yang diminta jika valid
            if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL)) {
                return redirect($redirect);
            }

            // Redirect default ke home
            return redirect()->route('home');
        }

        return back()->withErrors(['whatsapp_number' => 'Nomor WhatsApp atau password salah']);
    }

    // Menampilkan halaman registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi pengguna dengan validasi dan pengiriman OTP
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'whatsapp_number' => 'required|string|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $registrationData = $request->only(['name', 'whatsapp_number', 'password']);
        $request->session()->put('registration_data', $registrationData);

        // Kirim OTP untuk verifikasi registrasi
        $result = $this->otpService->sendOtp($request->whatsapp_number, true);

        if ($result['success']) {
            return redirect()->route('register.verify')->with('status', $result['message']);
        }

        return back()->withErrors(['whatsapp_number' => $result['message']]);
    }

    // Menampilkan halaman verifikasi OTP registrasi
    public function showRegisterVerify(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['general' => 'Silakan isi form registrasi terlebih dahulu']);
        }

        return view('auth.register-verify')->with([
            'whatsapp_number' => $registrationData['whatsapp_number'],
        ]);
    }

    // Verifikasi OTP dan menyelesaikan registrasi
    public function verifyRegisterOtp(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['general' => 'Sesi registrasi telah kadaluarsa. Silakan daftar kembali.']);
        }

        $request->validate([
            'otp_code' => 'required|string|digits:6',
        ]);

        // Verifikasi OTP
        $otpResult = $this->otpService->verifyOtp($registrationData['whatsapp_number'], $request->otp_code);

        if (!$otpResult['success']) {
            return back()->withErrors(['otp_code' => $otpResult['message']]);
        }

        // Buat user baru setelah verifikasi berhasil
        $user = User::create([
            'name' => $registrationData['name'],
            'whatsapp_number' => $registrationData['whatsapp_number'],
            'password' => Hash::make($registrationData['password']),
        ]);

        $request->session()->forget('registration_data');

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    // Proses logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar dari akun!');
    }

    // Menampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.wa');
    }

    // Mengirim link reset password via OTP
    public function sendResetLink(Request $request)
    {
        $request->validate(['whatsapp_number' => 'required|string']);

        $result = $this->otpService->sendOtp($request->whatsapp_number);

        if ($result['success']) {
            // Simpan nomor WhatsApp di session untuk verifikasi
            $request->session()->put('reset_whatsapp_number', $request->whatsapp_number);
            return redirect()->route('password.reset')->with('status', $result['message']);
        }

        return back()->withErrors(['whatsapp_number' => $result['message']]);
    }

    // Menampilkan form reset password
    public function showResetPasswordForm(Request $request)
    {
        $whatsapp_number = $request->session()->get('reset_whatsapp_number');

        if (!$whatsapp_number) {
            return redirect()->route('password.request')->withErrors(['whatsapp_number' => 'Silakan minta kode OTP terlebih dahulu']);
        }

        return view('auth.passwords.reset')->with([
            'whatsapp_number' => $whatsapp_number,
        ]);
    }

    // Proses reset password dengan verifikasi OTP
    public function resetPassword(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string',
            'otp_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verifikasi OTP untuk reset password
        $otpResult = $this->otpService->verifyOtp($request->whatsapp_number, $request->otp_code);

        if (!$otpResult['success']) {
            return back()->withErrors(['otp_code' => $otpResult['message']]);
        }

        // Cari user berdasarkan nomor WhatsApp
        $user = User::where('whatsapp_number', $request->whatsapp_number)->first();

        if (!$user) {
            return back()->withErrors(['whatsapp_number' => 'Nomor WhatsApp tidak terdaftar']);
        }

        // Update password dan remember token
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        $request->session()->forget('reset_whatsapp_number');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
