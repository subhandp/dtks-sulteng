<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtksImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtks_imports', function (Blueprint $table) {
            $table->id();
            $table->string('no_tiket');
            $table->string('filename');
            $table->string('filepath');
            $table->string('jumlah_baris');
            $table->string('status_import',100);
            $table->mediumText('keterangan');
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
        Schema::dropIfExists('dtks_imports');
    }
}
