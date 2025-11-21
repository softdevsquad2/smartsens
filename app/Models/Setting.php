<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'tbl_settings';

    protected $primaryKey = 'id_setting';

    protected $fillable = [
        'nama_setting',
        'nilai_setting',
        'keterangan',
    ];

    // Helper method untuk mendapatkan setting berdasarkan nama
    public static function getSetting($nama)
    {
        $setting = self::where('nama_setting', $nama)->first();

        return $setting ? $setting->nilai_setting : null;
    }

    // Helper method untuk menyimpan setting
    public static function setSetting($nama, $nilai, $keterangan = null)
    {
        return self::updateOrCreate(
            ['nama_setting' => $nama],
            ['nilai_setting' => $nilai, 'keterangan' => $keterangan]
        );
    }
}
