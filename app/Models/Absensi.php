<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'tbl_absensi';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'longitude_masuk',
        'latitude_masuk',
        'longitude_pulang',
        'latitude_pulang',
        'status_masuk',
        'status_pulang',
        'foto_masuk',
        'foto_pulang',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
