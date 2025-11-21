<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sholat extends Model
{
    protected $table = 'tbl_sholat';

    protected $primaryKey = 'id_sholat';

    public $timestamps = false; // karena tabel ini tidak punya kolom created_at & updated_at

    protected $fillable = [
        'id_siswa',
        'card_code',
        'masuk',
        'tanggal',
        'dzuhur_masuk',
        'dzuhur_keluar',
        'ashar_masuk',
        'ashar_keluar',
        'status_dzuhur',
        'status_ashar',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
