<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'vouchers';
    
    /**
    * Run the migrations.
    * @table vouchers
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('number')->nullable();
            $table->float('amount')->nullable();
            $table->tinyInteger('type')->nullable()->default('0');
            $table->string('currency')->length(45)->nullable()->default('ريال');
            $table->tinyInteger('status')->nullable()->default('0');
            $table->date('voucher_date')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('contract_id')->nullable();
            $table->unsignedInteger('cv_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->string('voucherable_type')->length(45)->nullable();
            $table->unsignedBigInteger('voucherable_id')->nullable();
            
            $table->unsignedBigInteger('advance_id')->nullable();
            $table->index('advance_id');
            $table->foreign('advance_id')->references('id')->on('advances')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unsignedInteger('year_id');
            $table->index('year_id');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade')->onUpdate('cascade');
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