<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'entries';
    
    /**
    * Run the migrations.
    * @table entries
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigIncrements('id');
            $table->tinyInteger('type')->nullable()->default('1');
            $table->float('amount');
            $table->text('details')->nullable();
            $table->date('entry_date')->nullable();
            $table->unsignedBigInteger('entry_id')->nullable();
            $table->unsignedBigInteger('entryable_id')->nullable();
            $table->string('entryable_type')->nullable();
            // $table->unsignedInteger('user_id');
            
            $table->index(["entry_id"], 'fk_entries_entries1_idx');
            
            // $table->index(["user_id"], 'fk_entries_users1_idx');

            $table->unsignedInteger('year_id');
            $table->index(["year_id"], 'fk_' . $this->tableName . '_years_idx');
            $table->foreign('year_id', 'fk_' . $this->tableName . '_years_idx')
                ->references('id')->on('years')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('entry_id', 'fk_entries_entries1_idx')
                ->references('id')->on('entries')
                ->onDelete('no action')
                ->onUpdate('no action');
            
            // $table->foreign('user_id', 'fk_entries_users1_idx')
            //     ->references('id')->on('users')
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