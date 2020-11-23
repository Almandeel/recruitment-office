<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'logs';

    /**
     * Run the migrations.
     * @table logs
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('operation')->length(10)->nullable();
            $table->longText('model')->nullable();
            $table->string('details')->nullable();
            $table->string('logable_type');
            $table->unsignedInteger('logable_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
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
