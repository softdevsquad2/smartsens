<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    use HasFactory;

    protected $table = 'tbl_wali_kelas';

    protected $primaryKey = 'id_wali_kelas';

    protected $fillable = ['id_kelas', 'nama', 'nip'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_wali_kelas');
    }
}
