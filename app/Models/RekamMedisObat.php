<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedisObat extends Model
{
    protected $table = 'tbl_rekam_medis_obat';

    protected $primaryKey = 'id_rekam_medis_obat';

    protected $fillable = ['id_rekam_medis', 'id_obat', 'jumlah'];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
