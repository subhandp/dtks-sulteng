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
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_fcu')->unique();
            $table->string('desa_kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('email',50)->nullable();
            $table->string('nama_ketua_fcu')->nullable();
            $table->string('no_hp_ketua_fcu',20)->nullable();
            $table->string('legalitas_fcu')->nullable();
            $table->integer('jumlah_keluarga_pionir')->nullable();
            $table->integer('jumlah_keluarga_plasma')->nullable();
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
        Schema::dropIfExists('psks_fcus');
    }
}
