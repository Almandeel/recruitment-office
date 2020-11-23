<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'suggestions';

    /**
     * Run the migrations.
     * @table suggestions
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->text('content')->nullable();
            $table->boolean('useful')->nullable()->default('0');
            $table->boolean('seen')->nullable()->default('0');
            $table->unsignedInteger('user_id');

            $table->index(["user_id"], 'fk_suggestions_users1_idx');


            $table->foreign('user_id', 'fk_suggestions_users1_idx')
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
       Schema::dropIfExists($this->tableName);
     }
}
