<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPsks extends Model
{
    use HasFactory;

    protected $fillable = ['jenis','detail'];
}
