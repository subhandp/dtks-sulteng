<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksFcusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_fcus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fcu');
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('email',50);
            $table->string('nama_ketua_fcu');
            $table->string('no_hp_ketua_fcu',20);
            $table->string('legalitas_fcu');
            $table->integer('jumlah_keluarga_pionir');
            $table->integer('jumlah_keluarga_plasma');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psks_fcus');
    }
}
