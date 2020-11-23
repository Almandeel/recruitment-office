<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillCvTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bill_cv';

    /**
     * Run the migrations.
     * @table bill_cv
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->unsignedInteger('bill_id');
            $table->unsignedInteger('cv_id');
            $table->float('amount')->nullable()->default(0);
            $table->float('amount_in_riyal')->nullable()->default(0);


            $table->index(["cv_id"], 'fk_bills_has_cv_cv1_idx');

            $table->index(["bill_id"], 'fk_bills_has_cv_bills1_idx');


            $table->foreign('bill_id', 'fk_bills_has_cv_bills1_idx')
                ->references('id')->on('bills')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('cv_id', 'fk_bills_has_cv_cv1_idx')
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
