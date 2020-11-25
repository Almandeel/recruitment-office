<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTafweedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tafweeds', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->date('contract_date'); 
            $table->integer('contract_id'); 
            $table->integer('customer_id'); 
            $table->text('visa');   
            $table->string('phone')->length(50); 
            $table->string('gender')->length(50);  
            $table->text('addr')->nullable(); 
            $table->float('salary');
            $table->string('marketer')->length(200); 
            $table->float('comm'); 
            $table->string('identification_num'); 
            $table->integer('country_id'); 
            $table->string('office');  
            $table->string('recruitment_cv_name');
            $table->string('recruitment_cv_passport');
            $table->string('injaz_num');
            $table->float('injaz_cost');
            $table->string('contract_num');
            $table->text('notes')->nullable(); 
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
        Schema::dropIfExists('tafweeds');
    }
}
