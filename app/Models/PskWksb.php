<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PskWksb extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_wksmb',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email', 
        'nama_ketua_wksbm',
        'legalitas_wksbm',
        'jumlah_pengurus',
        'jumlah_anggota',
        'jenis_kegiatan'
    ];
}
