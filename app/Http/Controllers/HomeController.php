<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $firstName = $user ? \Illuminate\Support\Str::before($user->name, ' ') : '';

        return view('user.home', ['firstName' => $firstName]);
    }
}
