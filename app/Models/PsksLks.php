<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksLks extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lks',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email',
        'nama_ketua_lks',
        'legalitas_lks',
        'posisi_lks',
        'ruang_lingkup',
        'jenis_kegiatan',
    ];
}
