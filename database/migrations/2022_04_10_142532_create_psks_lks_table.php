<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksLksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_lks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lks');
            $table->string('desa_kelurahan');
            $table->string('kecamatan');
            $table->string('no_hp',20);
            $table->string('email',50);
            $table->string('nama_ketua_lks');
            $table->string('legalitas_lks');
            $table->string('posisi_lks');
            $table->string('ruang_lingkup');
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
        Schema::dropIfExists('psks_lks');
    }
}
