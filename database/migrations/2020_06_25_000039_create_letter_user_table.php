<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterUserTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'letter_user';
    
    /**
    * Run the migrations.
    * @table letter_user
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->tinyInteger('seen')->nullable()->default(0);
            $table->tinyInteger('box')->nullable()->default(1);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->unsignedInteger('letter_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            
            $table->index(["user_id"], 'fk_letters_has_users_users1_idx');
            
            $table->index(["letter_id"], 'fk_letters_has_users_letters1_idx');
            
            
            $table->foreign('letter_id', 'fk_letters_has_users_letters1_idx')
            ->references('id')->on('letters')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('user_id', 'fk_letters_has_users_users1_idx')
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