<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRDtksJenisPmksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r_dtks_jenis_pmks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pmks_data_id');
            $table->unsignedBigInteger('jenis_pmks_id');
            $table->foreign('pmks_data_id')->references('id')->on('pmks_data');
            $table->foreign('jenis_pmks_id')->references('id')->on('jenis_pmks');
            $table->index(['pmks_data_id', 'jenis_pmks_id']);
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
        Schema::dropIfExists('r_dtks_jenis_pmks');
    }
}
