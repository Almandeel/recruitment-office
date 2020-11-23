<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskUserTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'task_user';

    /**
     * Run the migrations.
     * @table task_user
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('user_id');

            $table->index(["user_id"], 'fk_tasks_has_users_idx');

            $table->index(["task_id"], 'fk_tasks_has_users_tasks1_idx');


            $table->foreign('task_id', 'fk_tasks_has_users_tasks1_idx')
                ->references('id')->on('tasks')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id')
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
