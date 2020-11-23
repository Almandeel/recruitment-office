<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('office_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->string('username', 45);
            $table->string('password');
            $table->string('phone')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedInteger('office_id')->nullable();
            $table->rememberToken();

            $table->index(["office_id"], 'fk_office_users_offices_idx');

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
        Schema::dropIfExists('users');
    }
}
