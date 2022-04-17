<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksKt extends Model
{
    use HasFactory;

    public $table = "psks_kts";

    protected $fillable = [
        'dtks_import_id',
        'nama_kt',
        'kabupaten_kota',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email',
        'nama_ketua_kt',
        'legalitas_kt',
        'klasifikasi_kt',
        'jumlah_pengurus',
        'jenis_kegiatan',
        'created_at', 'updated_at'
    ];
}
