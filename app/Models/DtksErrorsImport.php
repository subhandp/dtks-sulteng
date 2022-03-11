<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtksErrorsImport extends Model
{
    use HasFactory;

    protected $fillable = ['dtks_import_id','row', 'attribute', 'values', 'errors'];
}
