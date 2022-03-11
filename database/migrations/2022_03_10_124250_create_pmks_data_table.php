<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmksDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pmks_data', function (Blueprint $table) {
            $table->id();
            $table->string('iddtks');
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('kecamatan');
            $table->string('desa_kelurahan');
            $table->mediumText('alamat');
            $table->string('dusun');
            $table->string('rt',5);
            $table->string('rw',5);
            $table->string('nomor_kk',20);
            $table->string('nomor_nik',20);
            $table->string('nama');
            $table->string('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('jenis_kelamin');
            $table->string('nama_ibu_kandung');
            $table->string('hubungan_keluarga');
            $table->string('tahun_data');
            $table->string('jenis_pmks');
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
        Schema::dropIfExists('pmks_data');
    }
}
