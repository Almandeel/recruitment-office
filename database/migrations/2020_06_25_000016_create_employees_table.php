<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'employees';

    /**
     * Run the migrations.
     * @table employees
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('line')->nullable();
            $table->tinyInteger('public_line')->default(0);
            $table->float('salary')->nullable()->default('0');
            $table->date('started_at');
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('department_id');

            $table->index(["position_id"], 'fk_employees_positions1_idx');
            $table->index(["department_id"], 'fk_employees_departments1_idx');


            // $table->foreign('position_id', 'fk_employees_positions1_idx')
            //     ->references('id')->on('positions')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');


            // $table->foreign('department_id', 'fk_employees_departments1_idx')
            //     ->references('id')->on('departments')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');
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
