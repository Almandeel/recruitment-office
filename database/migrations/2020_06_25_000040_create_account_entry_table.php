<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountEntryTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'account_entry';

    /**
     * Run the migrations.
     * @table account_entry
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->bigIncrements('id');
            $table->float('amount');
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('account_id');
            $table->tinyInteger('side')->nullable()->default('0');
            // $table->unsignedInteger('year_id')->nullable();

            $table->index(["account_id"], 'fk_account_entry_accounts1_idx');

            // $table->index(["entry_id"], 'fk_account_entry_entries1_index');

            // $table->index(["year_id"], 'fk_account_entry_years1_idx');


            // $table->foreign('entry_id', 'fk_account_entry_entries1_index')
            //     ->references('id')->on('entries')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');

            $table->foreign('account_id', 'fk_account_entry_accounts1_idx')
                ->references('id')->on('accounts')
                ->onDelete('no action')
                ->onUpdate('no action');

            // $table->foreign('year_id', 'fk_account_entry_years1_idx')
            //     ->references('id')->on('years')
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
