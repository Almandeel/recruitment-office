<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvWarehouseTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cv_warehouse';

    /**
     * Run the migrations.
     * @table cv_warehouse
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->unsignedInteger('cv_id');
            $table->unsignedInteger('warehouse_id');
            $table->tinyInteger('status')->nullable()->default('0');
            $table->date('entry_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->text('entry_note')->nullable();
            $table->text('exit_note')->nullable();

            $table->index(["warehouse_id"], 'fk_cv_has_warehouses_warehouses1_idx');

            $table->index(["cv_id"], 'fk_cv_has_warehouses_cv1_idx');


            $table->foreign('cv_id', 'fk_cv_has_warehouses_cv1_idx')
                ->references('id')->on('cvs')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('warehouse_id', 'fk_cv_has_warehouses_warehouses1_idx')
                ->references('id')->on('warehouses')
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
