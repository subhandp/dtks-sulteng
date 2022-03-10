<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmksData extends Model
{
    use HasFactory;

    protected $fillable = [
        'iddtks', 'provinsi', 'kabupaten_kota', 'kecamatan', 'desa_kelurahan', 'alamat', 'dusun', 'rt', 'rw',
        'nomor_kk', 'nomor_nik', 'nama', 'tanggal_lahir', 'tempat_lahir', 'jenis_kelamin', 'nama_ibu_kandung',
        'hubungan_keluarga', 'tahun_data', 'jenis_pmks'
    ];
}
