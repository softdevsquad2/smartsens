<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPrestasi extends Model
{
    protected $table = 'tbl_jenis_prestasi';

    protected $fillable = [
        'nama_prestasi',
        'poin_prestasi',
        'keterangan',
    ];

    public function rekamPrestasi(): HasMany
    {
        return $this->hasMany(RekamPrestasi::class, 'id_jenis_prestasi', 'id');
    }
}
