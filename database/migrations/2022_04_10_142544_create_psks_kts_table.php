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
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_kt')->unique();
            $table->string('desa_kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('no_hp',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('nama_ketua_kt')->nullable();
            $table->string('legalitas_kt')->nullable();
            $table->string('klasifikasi_kt')->nullable();
            $table->integer('jumlah_pengurus')->nullable();
            $table->string('jenis_kegiatan')->nullable();
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
        Schema::dropIfExists('psks_kts');
    }
}
