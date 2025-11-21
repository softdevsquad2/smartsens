<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StokObatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_obat' => 'required|exists:tbl_obat,id_obat',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'expired_date' => 'nullable|date|after:tanggal_masuk',
        ];
    }
}
