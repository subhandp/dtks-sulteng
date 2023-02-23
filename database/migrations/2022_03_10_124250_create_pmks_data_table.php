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
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('iddtks');
            $table->string('provinsi')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa_kelurahan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('nomor_kk')->nullable();
            $table->string('nomor_nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->string('hubungan_keluarga')->nullable();
            $table->string('tahun_data')->nullable();
            $table->string('jenis_pmks')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('keterangan_padan')->nullable(); 
            $table->string('bansos_bpnt')->nullable(); 
            $table->string('bansos_pkh')->nullable(); 
            $table->string('bansos_bpnt_ppkm')->nullable(); 
            $table->string('pbi_jkn')->nullable(); 
        
            $table->timestamps();
            $table->index(['kabupaten_kota', 'jenis_pmks','tahun_data']);

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
