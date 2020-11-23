<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'vacations';

    /**
     * Run the migrations.
     * @table vacations
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
            $table->text('details')->nullable();
            $table->boolean('accepted')->nullable()->default('0');
            $table->boolean('payed')->nullable()->default('0');
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->unsignedInteger('employee_id');

            $table->index(["employee_id"], 'fk_vacations_employees1_idx');


            $table->foreign('employee_id', 'fk_vacations_employees1_idx')
                ->references('id')->on('employees')
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
