<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custodies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount')->nullable();
            // $table->string('currency')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            // $table->unsignedInteger('employee_id');
            $table->unsignedInteger('year_id');
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
        Schema::dropIfExists('custodies');
    }
}
