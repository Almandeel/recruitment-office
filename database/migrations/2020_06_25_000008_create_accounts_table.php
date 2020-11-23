<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'accounts';
    
    /**
    * Run the migrations.
    * @table accounts
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->integer('number')->nullable();
            $table->string('name')->length(45)->nullable();
            $table->tinyInteger('type')->default('0');
            $table->tinyInteger('side')->default('0');
            $table->unsignedInteger('main_account')->nullable();
            $table->unsignedInteger('final_account')->nullable();
            
            $table->bigInteger('accountable_id')->nullable();
            $table->string('accountable_type')->nullable();
            
            
            $table->index(["final_account"], 'fk_accounts_accounts2_idx');
            
            $table->index(["main_account"], 'fk_accounts_accounts1_idx');
            
            
            $table->foreign('main_account', 'fk_accounts_accounts1_idx')
            ->references('id')->on('accounts')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('final_account', 'fk_accounts_accounts2_idx')
            ->references('id')->on('accounts')
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