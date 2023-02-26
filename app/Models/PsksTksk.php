<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksTksk extends Model
{
    use HasFactory;

    public $table = "psks_tksks";

    protected $fillable = [
        'dtks_import_id',
        'no_induk_tksk_a',
        'no_induk_tksk_b',
        'kecamatan',
        'nama',
        'nama_ibu_kandung',
        'nomor_nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_rumah',
        'no_hp',
        'pendidikan_terakhir',
        'kabupaten_kota',
        'tahun_pengangkatan_tksk',
        'mulai_aktif',
        'legalitas_sertifikat',
        'jenis_diklat_yg_diikuti',
        'pendampingan',
        'created_at', 'updated_at'
    ];
}


