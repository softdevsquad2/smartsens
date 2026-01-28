<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamPelanggaran extends Model
{
    protected $table = 'tbl_rekam_pelanggaran';

    protected $fillable = [
        'id_siswa',
        'id_pelanggaran',
        'tanggal_pelanggaran',
        'foto_pelanggaran',
        'id_user',
        'pelapor',
    ];

    protected $casts = [
        'tanggal_pelanggaran' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class, 'id_pelanggaran', 'id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
