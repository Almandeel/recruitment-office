<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->string('username', 45);
            $table->string('password');
            $table->boolean('status')->default('1');
            $table->unsignedInteger('employee_id')->nullable();
            $table->rememberToken();

            $table->index(["employee_id"], 'fk_users_employees_idx');

            $table->timestamps();


            $table->foreign('employee_id', 'fk_users_employees_idx')
                ->references('id')->on('employees')
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
        Schema::dropIfExists('users');
    }
}
