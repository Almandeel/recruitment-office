<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearsTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'years';
    
    /**
    * Run the migrations.
    * @table years
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->date('opened_at')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->tinyInteger('taxes')->nullable()->default(1);
            $table->date('closed_at')->nullable();
            $table->unsignedInteger('default_cash')->nullable();
            $table->unsignedInteger('default_bank')->nullable();
            $table->unsignedInteger('default_expenses')->nullable();
            $table->unsignedInteger('default_revenues')->nullable();
            $table->unsignedInteger('last_year')->nullable();
            $table->timestamps();
            $table->boolean('active')->default(1);
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}