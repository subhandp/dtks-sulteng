<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksTksk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_psm',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'nik_no_ktp',
        'alamat_rumah',
        'no_hp',
        'email',
        'mulai_aktif',
        'legalitas_sertifikat',
        'jenis_diklat_yg_diikuti',
        'pendampingan'
    ];
}
