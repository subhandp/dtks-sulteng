<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisPmks;
class Charts extends Model
{
    use HasFactory;

    protected $fillable = ['indonesia_cities_id','jenis_pmks_id','total', 'created_at', 'updated_at'];

    public function indonesiaCities()
    {
        // return $this->belongsTo(DtksImport::class,'indonesia_cities_id','id');
    }

    public function jenis_pmks()
    {
        return $this->belongsTo(JenisPmks::class,'jenis_pmks_id','id');
    }
}
