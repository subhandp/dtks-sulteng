<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksPsmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_psms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_psm')->unique();
            $table->string('jenis_kelamin',2)->nullable();
            $table->string('pendidikan_terakhir',50)->nullable();
            $table->string('nik_no_ktp',50)->nullable();
            $table->string('alamat_rumah')->nullable();
            $table->string('no_hp',20)->nullable();
            $table->string('email',50)->nullable();
            $table->string('mulai_aktif',5)->nullable();
            $table->string('legalitas_sertifikat',5)->nullable();
            $table->string('jenis_diklat_yg_diikuti',5)->nullable();
            $table->string('pendampingan', 20)->nullable();
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
        Schema::dropIfExists('psks_psms');
    }
}
