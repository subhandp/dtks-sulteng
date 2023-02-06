<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchSaved extends Model
{
    use HasFactory;
    public $table = "search_saveds";

    protected $fillable = [
        'user_id',
        'kabupaten_kota',
        'kecamatan',
        'desa_kelurahan',
        'umur',
        'tahun_data',
        'jenis_pmks',
        'created_at', 'updated_at'
    ];  


}
