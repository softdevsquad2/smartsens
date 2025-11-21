<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan(Request $request)
    {
        $qr = $request->qr_code;

        $siswa = User::where('qr_code', $qr)->first();

        if (!$siswa) {
            return back()->with('error', 'QR Code tidak valid!');
        }

        // Simpan id_user ke session agar konsisten dengan controller lain
        session(['peminjam_id' => $siswa->id_user]);

        return redirect()->route('pinjam.checkout')
            ->with('success', 'Siswa ditemukan: ' . $siswa->nama);
    }
}
