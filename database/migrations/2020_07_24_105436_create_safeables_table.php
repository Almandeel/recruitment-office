<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafeablesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('safeables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('safe_id');
            $table->unsignedBigInteger('safeable_id');
            $table->string('safeable_type');
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
        Schema::dropIfExists('safeables');
    }
}