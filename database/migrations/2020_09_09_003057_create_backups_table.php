<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('tables');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->index(["user_id"], 'fk_backups_users_idx');


            $table->foreign('user_id', 'fk_backups_users_idx')
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
        Schema::dropIfExists('backups');
    }
}
