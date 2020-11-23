<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'salaries';
    
    /**
    * Run the migrations.
    * @table salaries
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigIncrements('id');
            $table->string('month', 20)->nullable();
            $table->float('total')->nullable()->default('0');
            $table->float('debts')->nullable()->default('0');
            $table->float('bonus')->nullable()->default('0');
            $table->float('deducations')->nullable()->default('0');
            $table->float('net')->nullable()->default('0');
            $table->unsignedInteger('employee_id');
            $table->tinyInteger('status')->nullable()->default('0');
            // $table->unsignedInteger('entry_id');
            $table->unsignedInteger('user_id')->nullable();
            
            // $table->index(["entry_id"], 'fk_salaries_entries1_idx1');
            
            $table->index(["employee_id"], 'fk_salaries_employees1_idx');
            
            $table->index(["user_id"], 'fk_salaries_users1_idx');

            $table->unsignedInteger('year_id');
            $table->index(["year_id"], 'fk_' . $this->tableName . '_years1_idx');
            $table->foreign('year_id', 'fk_' . $this->tableName . '_years1_idx')
                ->references('id')->on('years')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            
            // $table->foreign('entry_id', 'fk_salaries_entries1_idx1')
            //     ->references('id')->on('entries')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');
            
            $table->foreign('user_id', 'fk_salaries_users1_idx')
            ->references('id')->on('users')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('employee_id', 'fk_salaries_employees1_idx')
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