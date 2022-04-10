<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePskFcsr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_fcsr', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fcsr');
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('no_hp',20);
            $table->string('email',50);
            $table->string('nama_ketua_fcsr');
            $table->string('legalitas_fcsr');
            $table->integer('jumlah_pengurus');
            $table->integer('jumlah_anggota');
            $table->integer('jumlah_csr_kesos_perusahaan');
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
        //
    }
}
