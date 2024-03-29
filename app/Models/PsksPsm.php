<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksPsm extends Model
{
    use HasFactory;
    
    public $table = "psks_psms";

    protected $fillable = [
        'dtks_import_id',
        'nama_psm',
        'kabupaten_kota',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'nik_no_ktp',
        'alamat_rumah',
        'no_hp',
        'email',
        'mulai_aktif',
        'legalitas_sertifikat',
        'jenis_diklat_yg_diikuti',
        'pendampingan',
        'created_at', 'updated_at'
    ];
}
