<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'attendance';

    /**
     * Run the migrations.
     * @table attendance
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->date('attend_date')->nullable();
            $table->boolean('attended')->nullable()->default('0');
            $table->string('notes', 45)->nullable();
            $table->unsignedInteger('employee_id');

            $table->index(["employee_id"], 'fk_attendance_employees1_idx');


            $table->foreign('employee_id', 'fk_attendance_employees1_idx')
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
