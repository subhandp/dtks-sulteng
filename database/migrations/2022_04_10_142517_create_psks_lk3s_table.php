<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsksLk3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psks_lk3s', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lk3');
            $table->string('alamat_kantor');
            $table->string('email',50);
            $table->string('nama_ketua_lk3');
            $table->string('no_hp_ketua_lk3',20);
            $table->string('jenis_lk3');
            $table->string('legalitas_lk3');
            $table->integer('jumlah_tenaga_professional');
            $table->integer('jumlah_klien');
            $table->integer('jumlah_masalah_kasus');
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
        Schema::dropIfExists('psks_lk3s');
    }
}
