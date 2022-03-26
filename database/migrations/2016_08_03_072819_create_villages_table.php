<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create(config('laravolt.indonesia.table_prefix').'villages', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->char('code', 10)->unique();
        //     $table->char('district_code', 7);
        //     $table->string('name', 255);
        //     $table->text('meta')->nullable();
        //     $table->timestamps();

        //     $table->foreign('district_code')
        //         ->references('code')
        //         ->on(config('laravolt.indonesia.table_prefix').'districts')
        //         ->onUpdate('cascade')->onDelete('restrict');
        // });

        // Schema::create('charts', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('kabupaten_kota');
        //     $table->integer('total');
        //     $table->timestamps();
        // });

        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->char('indonesia_cities_id',5);
            $table->bigInteger('jenis_pmks_id')->nullable();
            $table->integer('total');
            $table->timestamps();
            $table->index(['indonesia_cities_id', 'jenis_pmks_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::drop(config('laravolt.indonesia.table_prefix').'villages');
        Schema::dropIfExists('charts');
    }
}
