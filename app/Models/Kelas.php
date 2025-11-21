<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'tbl_kelas';

    protected $primaryKey = 'id_kelas';

    protected $fillable = ['id_jurusan', 'nama_kelas'];


    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class, 'id_kelas');
    }
}