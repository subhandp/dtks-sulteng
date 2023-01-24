<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RDtksJenisPmks extends Model
{
    use HasFactory;
    public $table = "r_dtks_jenis_pmks";

    protected $fillable = [
        'pmks_data_id',
        'jenis_pmks_id',
        'created_at', 'updated_at'
    ];
}
