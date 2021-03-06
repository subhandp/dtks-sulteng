<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmksData extends Model
{
    use HasFactory;

    protected $fillable = [
        'dtks_import_id','iddtks', 'provinsi', 'kabupaten_kota', 'kecamatan', 'desa_kelurahan', 'alamat', 'dusun', 'rt', 'rw',
        'nomor_kk', 'nomor_nik', 'nama', 'tanggal_lahir', 'tempat_lahir', 'jenis_kelamin', 'nama_ibu_kandung',
        'hubungan_keluarga', 'tahun_data', 'jenis_pmks'
    ];

    public function dtksImports()
    {
        return $this->belongsTo(DtksImport::class,'dtks_import_id','id');
    }
}
