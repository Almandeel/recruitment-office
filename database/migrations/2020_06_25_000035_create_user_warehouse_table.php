<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWarehouseTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'user_warehouse';

    /**
     * Run the migrations.
     * @table user_warehouse
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('warehouse_id');

            $table->index(["warehouse_id"], 'fk_users_has_warehouses_warehouses1_idx');

            // $table->index(["user_id"], 'fk_users_has_warehouses_users1_idx');


            // $table->foreign('user_id', 'fk_users_has_warehouses_users1_idx')
            //     ->references('id')->on('users')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');

            $table->foreign('warehouse_id', 'fk_users_has_warehouses_warehouses1_idx')
                ->references('id')->on('warehouses')
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
