<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtksErrorsImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtks_errors_imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dtks_import_id');
            $table->string('attribute')->nullable();
            $table->unsignedInteger('row')->nullable();
            $table->string('values')->nullable();
            $table->text('errors')->nullable();
            $table->foreign('dtks_import_id')->references('id')->on('dtks_imports');
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
        Schema::dropIfExists('dtks_errors_imports');
    }
}
