<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KunjunganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
            'jenis_kunjungan' => 'required|in:sakit,cedera,pemeriksaan_rutin,konsultasi,izin_pulang',
            'keterangan' => 'nullable|string',
        ];

        // Jika jenis kunjungan adalah sakit atau cedera, tambahkan validasi field rekam medis
        if (in_array($this->jenis_kunjungan, ['sakit', 'cedera'])) {
            $rules = array_merge($rules, [
                'keluhan' => 'required|string',
                'diagnosis' => 'nullable|string',
                'tindakan' => 'nullable|string',
                'catatan' => 'nullable|string',
                'obat_diberikan' => 'nullable|array',
                'obat_diberikan.*.id_obat' => 'required_with:obat_diberikan|exists:tbl_obat,id_obat',
                'obat_diberikan.*.jumlah' => 'required_with:obat_diberikan|integer|min:1',
            ]);
        }

        return $rules;
    }
}
