<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamPrestasi extends Model
{
    protected $table = 'tbl_rekam_prestasi_siswa';

    protected $fillable = [
        'id_siswa',
        'id_jenis_prestasi',
        'tanggal_prestasi',
        'bukti_prestasi',
        'keterangan',
        'id_user',
        'pembimbing',
        'poin_diberikan',
    ];

    protected $casts = [
        'tanggal_prestasi' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function jenisPrestasi(): BelongsTo
    {
        return $this->belongsTo(JenisPrestasi::class, 'id_jenis_prestasi', 'id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
