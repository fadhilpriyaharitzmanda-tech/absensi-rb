<?php

namespace App\Http\Middleware;

use App\Models\ClassPin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            return $next($request);
        }

        if (session('siswa_pin_verified')) {
            $kodeKelas = session('siswa_kode_kelas');
            $currentVersion = \Illuminate\Support\Facades\Cache::remember(
                'class_pin_version_' . $kodeKelas,
                300,
                fn() => ClassPin::where('kode_kelas', $kodeKelas)->value('pin_version')
            );

            if ($currentVersion === null || session('siswa_pin_version') !== $currentVersion) {
                session()->forget(['siswa_pin_verified', 'siswa_kode_kelas', 'siswa_pin_version']);
                return redirect()->route('pin.form')
                    ->with('info', 'PIN kelas telah diubah, silakan masuk dengan PIN baru');
            }

            return $next($request);
        }

        return redirect()->route('pin.form');
    }
}
