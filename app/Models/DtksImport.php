<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtksImport extends Model
{
    use HasFactory;
    protected $fillable = ['no_tiket', 'filename', 'filepath', 'jumlah_baris', 'baris_selesai','status_import', 'keterangan'];
}
