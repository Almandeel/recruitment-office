<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'marketers';

    /**
     * Run the migrations.
     * @table marketers
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 45);
            $table->string('phone', 15)->nullable();
            $table->double('debt')->default(0);
            $table->double('credit')->default(0);
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
       Schema::dropIfExists($this->tableName);
     }
}
