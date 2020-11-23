<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            
            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('no action')
            ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutorials');
    }
}
