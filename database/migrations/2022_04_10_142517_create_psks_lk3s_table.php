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
            $table->unsignedBigInteger('dtks_import_id')->nullable();
            $table->string('nama_lk3')->unique();
            $table->string('alamat_kantor')->nullable();
            $table->string('email',50)->nullable();
            $table->string('nama_ketua_lk3')->nullable();
            $table->string('no_hp_ketua_lk3',20)->nullable();
            $table->string('jenis_lk3')->nullable();
            $table->string('legalitas_lk3')->nullable();
            $table->integer('jumlah_tenaga_professional')->nullable();
            $table->integer('jumlah_klien')->nullable();
            $table->integer('jumlah_masalah_kasus')->nullable();
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
        Schema::dropIfExists('psks_lk3s');
    }
}
