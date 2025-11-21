<?php

namespace App\Http\Requests;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;

class IzinPulangRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // 1. Jika id_siswa kosong tapi RFID diisi, cari otomatis dari tabel siswa
        if (! $this->filled('id_siswa') && $this->filled('rfid_input')) {
            $siswa = Siswa::where('card_code', $this->rfid_input)->first();
            if ($siswa) {
                $this->merge(['id_siswa' => $siswa->id_siswa]);
            }
        }

        // 2. Hilangkan spasi tak perlu
        if ($this->filled('id_siswa')) {
            $this->merge(['id_siswa' => trim($this->id_siswa)]);
        }
    }

    public function rules()
    {
        return [
            // pastikan tabel benar, lihat dari file SQL (biasanya "tbl_siswa")
            'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
            'keterangan' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'id_siswa.required' => 'Siswa Tidak Melakukan Presensi Masuk. Tidak dapat membuat Izin Pulang.',
            'id_siswa.exists' => 'Siswa tidak ditemukan di sistem.',
            'keterangan.required' => 'Alasan izin pulang harus diisi.',
        ];
    }
}