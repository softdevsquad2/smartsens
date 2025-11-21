<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'tbl_peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user',
        'id_barang',
        'jumlah',
        'tujuan',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function siswa()
    {
        return $this->hasOneThrough(
            Siswa::class,
            User::class,
            'id_user',      // foreign key di tbl_user
            'id_siswa',     // foreign key di tbl_siswa
            'id_user',      // local key di tbl_peminjaman
            'id_siswa'      // local key di tbl_user
        );
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }


    // Relasi ke detail peminjaman (multiple item lines)
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'id_peminjaman', 'id_peminjaman');
    }
}
