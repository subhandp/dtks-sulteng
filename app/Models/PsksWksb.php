<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksWksb extends Model
{
    use HasFactory;

    public $table = "psks_wksbs";

    protected $fillable = [
        'dtks_import_id',
        'nama_wksb',
        'kabupaten_kota',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email', 
        'nama_ketua_wksbm',
        'legalitas_wksbm',
        'jumlah_pengurus',
        'jumlah_anggota',
        'jenis_kegiatan',
        'created_at', 'updated_at'
    ];
}
