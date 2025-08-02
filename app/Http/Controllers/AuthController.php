<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        $credentials = $request->only('nik', 'password');

        // if (Auth::attempt($credentials, $request->has('remember'))) {
        //     return redirect()->route('dashboard.index')->with('alert','Selamat Datang');
        // }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // Periksa apakah statusenabled pengguna bernilai true
            if (Auth::user()->statusenabled == true) {
                if (in_array(Auth::user()->role, ['TRAINING CENTER'])){
                    return redirect()->route('production.index')->with('alert', 'Selamat Datang');
                }elseif (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK'])){
                    return redirect()->route('dashboard.index')->with('alert', 'Selamat Datang');
                }else{
                    return redirect()->route('p2h.index')->with('alert', 'Selamat Datang');
                }

            } else {
                // Logout jika statusenabled adalah false
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()->with('info', 'Akun Anda tidak diaktifkan.');
            }
        }

        return redirect()->back()->with('login', 'NIK atau password salah');

        // return back()->withErrors([
        //     'nik' => 'NIK atau password salah.',
        // ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar');
    }
}
