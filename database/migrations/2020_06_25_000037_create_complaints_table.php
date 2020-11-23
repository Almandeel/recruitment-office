<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'complaints';

    /**
     * Run the migrations.
     * @table complaints
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('cause')->nullable();
            $table->tinyInteger('status')->nullable()->default('0');
            $table->tinyInteger('from')->nullable();
            $table->unsignedInteger('cv_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->timestamps();

            $table->index(["customer_id"], 'fk_complaints_customers1_idx');

            $table->index(["cv_id"], 'fk_complaints_cv1_idx');


            $table->foreign('cv_id', 'fk_complaints_cv1_idx')
                ->references('id')->on('cvs')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('customer_id', 'fk_complaints_customers1_idx')
                ->references('id')->on('customers')
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
