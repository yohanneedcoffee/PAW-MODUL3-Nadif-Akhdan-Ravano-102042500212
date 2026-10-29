<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    // TODO: Tentukan kolom tabel anggota yang dapat diisi melalui mass assignment ($fillable)
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }
}
