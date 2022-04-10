<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePskWksbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psk_wksbs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wksmb');
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('no_hp',20);
            $table->string('email',50);
            $table->string('nama_ketua_wksbm');
            $table->string('legalitas_wksbm');
            $table->integer('jumlah_pengurus');
            $table->integer('jumlah_anggota');
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
        Schema::dropIfExists('psk_wksbs');
    }
}
