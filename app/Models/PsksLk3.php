<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksLk3 extends Model
{
    use HasFactory;

    public $table = "psks_lk3s";

    protected $fillable = [
        'dtks_import_id',
        'nama_lk3',
        'kabupaten_kota',
        'alamat_kantor',
        'email',
        'nama_ketua_lk3',
        'no_hp_ketua_lk3',
        'jenis_lk3',
        'legalitas_lk3',
        'jumlah_tenaga_professional',
        'jumlah_klien',
        'jumlah_masalah_kasus',
        'created_at', 'updated_at'
    ];
}
