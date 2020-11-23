<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tasks';

    /**
     * Run the migrations.
     * @table tasks
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->string('name', 45)->nullable();
            $table->tinyInteger('status')->nullable()->default('0');
            $table->date('due_date')->nullable();
            $table->unsignedInteger('user_id');

            $table->index(["user_id"], 'fk_tasks_has_users_users1_idx');

            $table->foreign('user_id', 'fk_tasks_has_users_users1_idx')
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
