<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisPsks;
use App\Models\KabupatenKota;
class ChartsPsks extends Model
{
    use HasFactory;

    protected $fillable = ['indonesia_cities_id','jenis_psks_id','total', 'created_at', 'updated_at'];

    public function kabupaten_kota()
    {
        return $this->belongsTo(KabupatenKota::class,'indonesia_cities_id','id');
    }

    public function jenis_psks()
    {
        return $this->belongsTo(JenisPsks::class,'jenis_psks_id','id');
    }
}
