<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekamMedis extends Model
{
    protected $table = 'tbl_rekam_medis';

    protected $primaryKey = 'id_rekam_medis';

    protected $fillable = ['id_siswa', 'id_kunjungan', 'tanggal', 'keluhan', 'diagnosis', 'tindakan', 'catatan', 'obat_diberikan'];

    protected $casts = [
        'obat_diberikan' => 'json',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(KunjunganUks::class, 'id_kunjungan');
    }

    /**
     * Detail records melalui tbl_rekam_medis_obat
     */
    public function obat(): HasMany
    {
        return $this->hasMany(RekamMedisObat::class, 'id_rekam_medis');
    }

    /**
     * Many-to-Many relationship ke Obat
     */
    public function obatList(): BelongsToMany
    {
        return $this->belongsToMany(
            Obat::class,
            'tbl_rekam_medis_obat',
            'id_rekam_medis',
            'id_obat'
        )->withPivot('jumlah')
            ->withTimestamps();
    }
}
