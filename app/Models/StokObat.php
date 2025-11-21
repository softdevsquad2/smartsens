<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObat extends Model
{
    use HasFactory;

    protected $table = 'tbl_stok_obat';

    protected $primaryKey = 'id_stok';

    protected $fillable = ['id_obat', 'jumlah', 'tanggal_masuk', 'expired_date'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
