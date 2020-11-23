<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedBigInteger('authable_id');
            $table->string('authable_type');
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
        Schema::dropIfExists('authable');
    }
}
