<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCenterTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'account_center';

    /**
     * Run the migrations.
     * @table account_center
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigIncrements('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('center_id');

            $table->index(["center_id"], 'fk_accounts_has_centers_centers1_idx');

            $table->index(["account_id"], 'fk_accounts_has_centers_accounts1_idx');


            $table->foreign('account_id', 'fk_accounts_has_centers_accounts1_idx')
                ->references('id')->on('accounts')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('center_id', 'fk_accounts_has_centers_centers1_idx')
                ->references('id')->on('centers')
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
