<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePullsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'pulls';

    /**
     * Run the migrations.
     * @table pulls
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            // $table->text('cause')->nullable();
            $table->float('damages')->nullable()->default(0);
            $table->float('payed')->nullable()->default(0);
            // $table->boolean('confirmed')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('cv_id');
            $table->unsignedInteger('user_id');
            // $table->text('notes')->nullable();
            
            $table->unsignedBigInteger('advance_id')->nullable();
            $table->index(["advance_id"], 'fk_pulls_advances1_idx');
            $table->foreign('advance_id', 'fk_pulls_advances1_idx')
                ->references('id')->on('advances')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->index(["cv_id"], 'fk_pulls_cv1_idx');


            $table->foreign('cv_id', 'fk_pulls_cv1_idx')
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
