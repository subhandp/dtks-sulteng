<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksKtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_kts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kt');
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('no_hp',20);
            $table->string('email',50);
            $table->string('nama_ketua_kt');
            $table->string('legalitas_kt');
            $table->string('klasifikasi_kt');
            $table->integer('jumlah_pengurus');
            $table->string('jenis_kegiatan');
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
        Schema::dropIfExists('psks_kts');
    }
}
