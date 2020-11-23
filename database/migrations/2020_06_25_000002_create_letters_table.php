<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'letters';

    /**
     * Run the migrations.
     * @table letters
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->string('title', 45)->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('letter_id')->nullable();

            $table->index(["user_id"], 'fk_users_idx');
            $table->index(["letter_id"], 'fk_letter_idx');

            $table->foreign('user_id', 'fk_users_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');
            
            $table->foreign('letter_id', 'fk_letter_idx')
            ->references('id')->on('letters')
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
