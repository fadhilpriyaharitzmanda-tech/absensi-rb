<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'old_pin' => 'required|string|size:6',
            'pin' => 'required|string|size:6|confirmed',
        ], [
            'pin.confirmed' => 'Konfirmasi PIN baru tidak cocok, ketik ulang dengan benar.',
        ]);
        
        $classPin = ClassPin::where('kode_kelas', '11-RPL-B')->firstOrFail();

        if (!Hash::check($request->old_pin, $classPin->pin)) {
            return back()->withErrors(['old_pin' => 'PIN lama tidak sesuai.']);
        }

        if ($request->old_pin === $request->pin) {
            return back()->withErrors(['pin' => 'PIN baru harus berbeda dari PIN lama.']);
        }

        $classPin->update([
            'pin' => Hash::make($request->pin),
            'pin_updated_at' => now(),
            'pin_version' => $classPin->pin_version + 1,
        ]);

        \Illuminate\Support\Facades\Cache::forget('class_pin_version_11-RPL-B');

        return redirect()->back()
            ->with('success', 'PIN kelas berhasil diperbarui');
    }
}
