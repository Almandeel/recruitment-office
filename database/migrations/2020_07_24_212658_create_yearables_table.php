<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('year_id');
            $table->unsignedBigInteger('yearable_id');
            $table->string('yearable_type');
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
        Schema::dropIfExists('yearables');
    }
}
