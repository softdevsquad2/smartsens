<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_user';

    protected $primaryKey = 'id_user';

    public $timestamps = true;

    protected $fillable = [
        'id_wali_kelas',
        'id_siswa',
        'username',
        'password',
        'role',
        'card_code',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas');
    }

    // Helper method untuk mengecek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Helper method untuk mengecek apakah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Helper method untuk mengecek apakah guru
    public function isGuru()
    {
        return $this->role === 'guru';
    }

    // Helper method untuk mengecek apakah siswa
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    // Helper method untuk mengecek apakah uks
    public function isUks()
    {
        return $this->role === 'uks';
    }
}
