<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksFcsr extends Model
{
    use HasFactory;
    
    public $table = "psks_fcsrs";

    protected $fillable = [
        'dtks_import_id',
        'nama_fcsr',
        'kabupaten_kota',
        'desa_kelurahan',
        'kecamatan',
        'no_hp',
        'email',
        'legalitas_fcsr',
        'jumlah_pengurus',
        'jumlah_anggota',
        'jumlah_csr_kesos_perusahaan',
        'created_at', 'updated_at'
    ];

}
