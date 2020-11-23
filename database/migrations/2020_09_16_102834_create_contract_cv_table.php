<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractCvTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('contract_cv', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('cv_id');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            
            
            $table->index("contract_id");
            
            $table->index("cv_id");
            
            
            $table->foreign('contract_id')
            ->references('id')->on('contracts')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('cv_id')
            ->references('id')->on('cvs')
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
        Schema::dropIfExists('contract_cv');
    }
}