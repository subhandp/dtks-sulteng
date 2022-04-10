<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsksFcu extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'nama_fcu',
        'desa_kelurahan',
        'kecamatan',
        'email',
        'nama_ketua_fcu',
        'no_hp_ketua_fcu',
        'legalitas_fcu',
        'jumlah_keluarga_pionir',
        'jumlah_keluarga_plasma'
    ];

}
