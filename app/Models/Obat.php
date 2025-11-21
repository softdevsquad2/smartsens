<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'tbl_obat';

    protected $primaryKey = 'id_obat';

    protected $fillable = ['nama_obat', 'deskripsi', 'kategori', 'kadaluarsa_default'];

    public function stokObat(): HasMany
    {
        return $this->hasMany(StokObat::class, 'id_obat', 'id_obat');
    }

    /**
     * Many-to-Many relationship ke RekamMedis
     */
    public function rekamMedis(): BelongsToMany
    {
        return $this->belongsToMany(
            RekamMedis::class,
            'tbl_rekam_medis_obat',
            'id_obat',
            'id_rekam_medis'
        )->withPivot('jumlah')
            ->withTimestamps();
    }
}