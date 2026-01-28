<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rekam_pelanggaran extends Model
{
    protected $table = 'tbl_rekam_pelanggaran';
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class, 'id_pelanggaran', 'id');
    }
    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
