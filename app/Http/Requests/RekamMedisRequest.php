<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekamMedisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_siswa' => 'required|exists:tbl_siswa,id_siswa',
            'tanggal' => 'required|date',
            'keluhan' => 'required|string',
            'diagnosis' => 'nullable|string',
            'obat_diberikan.id_obat' => 'nullable|array',
            'obat_diberikan.id_obat.*' => 'nullable|exists:tbl_obat,id_obat',
            'obat_diberikan.jumlah' => 'nullable|array',
            'obat_diberikan.jumlah.*' => 'nullable|integer|min:1',
            'obat_diberikan.aturan_pakai' => 'nullable|array',
            'obat_diberikan.aturan_pakai.*' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'obat_diberikan.id_obat.*.required_if' => 'Obat harus dipilih',
            'obat_diberikan.id_obat.*.exists' => 'Obat tidak ditemukan',
            'obat_diberikan.jumlah.*.required_if' => 'Jumlah obat harus diisi',
            'obat_diberikan.jumlah.*.min' => 'Jumlah minimal 1',
        ];
    }
}
