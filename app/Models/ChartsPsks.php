<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisPsks;
class ChartsPsks extends Model
{
    use HasFactory;

    protected $fillable = ['indonesia_cities_id','jenis_psks_id','total', 'created_at', 'updated_at'];

    public function indonesiaCities()
    {
        // return $this->belongsTo(DtksImport::class,'indonesia_cities_id','id');
    }

    public function jenis_psks()
    {
        return $this->belongsTo(JenisPsks::class,'jenis_psks_id','id');
    }
}
