<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksFcu extends Model
{
    use HasFactory;

    public $table = "psks_fcus";


    protected $fillable = [
        'dtks_import_id',
        'nama_fcu',
        'kabupaten_kota',
        'desa_kelurahan',
        'kecamatan',
        'email',
        'nama_ketua_fcu',
        'no_hp_ketua_fcu',
        'legalitas_fcu',
        'jumlah_keluarga_pionir',
        'jumlah_keluarga_plasma',
        'created_at', 'updated_at'
    ];

}
