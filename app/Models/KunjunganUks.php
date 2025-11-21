<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganUks extends Model
{
    protected $table = 'tbl_kunjungan_uks';

    protected $primaryKey = 'id_kunjungan';

    protected $fillable = ['id_siswa', 'id_petugas_uks', 'tanggal', 'waktu', 'jenis_kunjungan', 'keterangan'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function petugasUks()
    {
        return $this->belongsTo(User::class, 'id_petugas_uks');
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'id_kunjungan');
    }
}
