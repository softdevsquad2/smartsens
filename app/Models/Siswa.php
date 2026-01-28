<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'tbl_siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = ['id_kelas', 'nama', 'jenis_kelamin', 'nisn', 'card_code', 'no_hp_ortu'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_user', 'id_user');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_siswa');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_siswa');
    }

    public function rekamPelanggaran()
    {
        return $this->hasMany(RekamPelanggaran::class, 'id_siswa', 'id_siswa');
    }

    public function rekamPrestasi()
    {
        return $this->hasMany(RekamPrestasi::class, 'id_siswa', 'id_siswa');
    }
}
