<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksTksksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_tksks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_psm');
            $table->string('jenis_kelamin',2);
            $table->string('pendidikan_terakhir',50);
            $table->string('nik_no_ktp',50);
            $table->string('alamat_rumah');
            $table->string('no_hp',20);
            $table->string('email',50);
            $table->string('mulai_aktif',5);
            $table->string('legalitas_sertifikat',5);
            $table->string('jenis_diklat_yg_diikuti',5);
            $table->string('peran_pendamping', 20);
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
        Schema::dropIfExists('psks_tksks');
    }
}
