<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'lines';

    /**
     * Run the migrations.
     * @table lines
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->tinyInteger('code')->nullable();
            $table->unsignedInteger('user_id');

            $table->index(["user_id"], 'fk_lines_users1_idx');

            $table->unique(["code"], 'code_UNIQUE');


            $table->foreign('user_id', 'fk_lines_users1_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
