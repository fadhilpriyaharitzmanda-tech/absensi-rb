<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClassPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class PinLoginController extends Controller
{
    public function showPinForm()
    {
        if (session('siswa_pin_verified')) {
            return redirect()->route('beranda');
        }
        return view('auth.pin');
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        $throttleKey = 'pin_verify|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'pin' => "Terlalu banyak percobaan PIN salah. Silakan tunggu {$seconds} detik.",
            ]);
        }

        $classPin = ClassPin::where('kode_kelas', '11-RPL-B')->first();

        if (!$classPin || !Hash::check($request->pin, $classPin->pin)) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['pin' => 'PIN salah, coba lagi.']);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        session([
            'siswa_pin_verified' => true,
            'siswa_kode_kelas'   => '11-RPL-B',
            'siswa_pin_version'  => $classPin->pin_version,
        ]);

        return redirect()->intended(route('beranda'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['siswa_pin_verified', 'siswa_kode_kelas', 'siswa_pin_version']);
        return redirect()->route('pin.form');
    }
}
