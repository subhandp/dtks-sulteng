<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksKt extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kt',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email',
        'nama_ketua_kt',
        'legalitas_kt',
        'klasifikasi_kt',
        'jumlah_pengurus',
        'jenis_kegiatan',
    ];
}
