<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksLk3 extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lk3',
        'alamat_kantor',
        'email',
        'nama_ketua_lk3',
        'no_hp_ketua_lk3',
        'jenis_lk3',
        'legalitas_lk3',
        'jumlah_tenaga_professional',
        'jumlah_klien',
        'jumlah_masalah_kasus',
    ];
}
