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
        Schema::create('psks_fcsrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_fcsr')->unique();
            $table->string('desa_kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('no_hp',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('nama_ketua_pengurus_fcsr')->nullable();
            $table->string('legalitas_fcsr')->nullable();
            $table->integer('jumlah_pengurus')->nullable();
            $table->integer('jumlah_anggota')->nullable();
            $table->integer('jumlah_csr_kesos_perusahaan')->nullable();
            $table->string('kabupaten_kota')->nullable();
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
        Schema::dropIfExists('psks_fcsrs');
    }
}
