<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'latitude' => Setting::getSetting('school_latitude'),
            'longitude' => Setting::getSetting('school_longitude'),
            'radius' => Setting::getSetting('attendance_radius') ?? 100,
            'waktu_masuk' => Setting::getSetting('jam_masuk') ?? '07:00',
            'waktu_terlambat' => Setting::getSetting('jam_terlambat') ?? '07:30',
            'waktu_pulang' => Setting::getSetting('jam_pulang') ?? '15:00',
            'pagination_pelanggaran' => Setting::getSetting('pagination_pelanggaran') ?? 10,
            'pagination_riwayat' => Setting::getSetting('pagination_riwayat') ?? 10,
            'pagination_unduh' => Setting::getSetting('pagination_unduh') ?? 15,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:1000',
            'waktu_masuk' => 'required|date_format:H:i',
            'waktu_terlambat' => 'required|date_format:H:i|after:waktu_masuk',
            'waktu_pulang' => 'required|date_format:H:i|after:waktu_terlambat',
            'pagination_pelanggaran' => 'required|integer|min:5|max:100',
            'pagination_riwayat' => 'required|integer|min:5|max:100',
            'pagination_unduh' => 'required|integer|min:5|max:100',
        ]);

        // Update atau create settings
        Setting::setSetting('school_latitude', $request->latitude, 'Latitude sekolah');
        Setting::setSetting('school_longitude', $request->longitude, 'Longitude sekolah');
        Setting::setSetting('attendance_radius', $request->radius, 'Radius absensi dalam meter');
        Setting::setSetting('jam_masuk', $request->waktu_masuk, 'Jam masuk sekolah');
        Setting::setSetting('jam_terlambat', $request->waktu_terlambat, 'Batas waktu masuk sebelum dianggap terlambat');
        Setting::setSetting('jam_pulang', $request->waktu_pulang, 'Jam pulang sekolah');
        Setting::setSetting('pagination_pelanggaran', $request->pagination_pelanggaran, 'Jumlah data per halaman di halaman pelanggaran');
        Setting::setSetting('pagination_riwayat', $request->pagination_riwayat, 'Jumlah data per halaman di halaman riwayat siswa');
        Setting::setSetting('pagination_unduh', $request->pagination_unduh, 'Jumlah data per halaman di halaman unduh laporan');

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan');
    }

    public function getCurrentSettings()
    {
        return [
            'school_latitude' => Setting::getSetting('school_latitude'),
            'school_longitude' => Setting::getSetting('school_longitude'),
            'attendance_radius' => Setting::getSetting('attendance_radius') ?? 100,
            'jam_masuk' => Setting::getSetting('jam_masuk') ?? '07:00',
            'jam_terlambat' => Setting::getSetting('jam_terlambat') ?? '07:30',
            'jam_pulang' => Setting::getSetting('jam_pulang') ?? '15:00',
        ];
    }
}
