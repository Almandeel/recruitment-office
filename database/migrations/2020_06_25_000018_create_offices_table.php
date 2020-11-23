<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'offices';

    /**
     * Run the migrations.
     * @table offices
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->string('name', 45);
            $table->boolean('status')->nullable()->default('1');
            $table->unsignedInteger('country_id');
            $table->string('email', 45)->nullable();
            $table->string('phone', 45)->nullable();
            $table->unsignedInteger('admin_id')->nullable();

            $table->index(["country_id"], 'fk_offices_countries1_idx');


            $table->foreign('country_id', 'fk_offices_countries1_idx')
                ->references('id')->on('countries')
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
       Schema::dropIfExists($this->tableName);
     }
}
