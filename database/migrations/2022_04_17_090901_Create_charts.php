<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->char('indonesia_cities_id',5);
            $table->bigInteger('jenis_pmks_id')->nullable();
            $table->integer('total');
            $table->timestamps();
            $table->index(['indonesia_cities_id', 'jenis_pmks_id']);
        });

        Schema::create('charts_psks', function (Blueprint $table) {
            $table->id();
            $table->char('indonesia_cities_id',5);
            $table->bigInteger('jenis_psks_id')->nullable();
            $table->integer('total');
            $table->timestamps();
            $table->index(['indonesia_cities_id', 'jenis_psks_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charts');
        Schema::dropIfExists('charts_psks');
    }
}
