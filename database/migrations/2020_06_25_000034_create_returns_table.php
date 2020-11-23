<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'returns';

    /**
     * Run the migrations.
     * @table returns
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->text('cause')->nullable();
            $table->boolean('counted')->nullable()->default('0');
            $table->float('payed')->default('0');
            $table->float('damages')->default('0');
            $table->unsignedInteger('cv_id');
            $table->unsignedInteger('user_id');
            
            $table->unsignedBigInteger('advance_id')->nullable();
            $table->index(["advance_id"], 'fk_returns_advances1_idx');
            $table->foreign('advance_id', 'fk_returns_advances1_idx')
                ->references('id')->on('advances')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->index(["cv_id"], 'fk_returns_cvs1_idx');
            $table->foreign('cv_id', 'fk_returns_cvs1_idx')
                ->references('id')->on('cvs')
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
