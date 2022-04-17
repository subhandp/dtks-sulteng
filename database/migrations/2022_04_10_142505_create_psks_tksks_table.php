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
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_tksk')->unique();
            $table->string('jenis_kelamin',2)->nullable();
            $table->string('pendidikan_terakhir',50)->nullable();
            $table->string('nik_no_ktp',50)->nullable();
            $table->string('alamat_rumah')->nullable();
            $table->string('no_hp',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('mulai_aktif')->nullable();
            $table->string('legalitas_sertifikat')->nullable();
            $table->string('jenis_diklat_yg_diikuti')->nullable();
            $table->string('pendampingan')->nullable();
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
        Schema::dropIfExists('psks_tksks');
    }
}
