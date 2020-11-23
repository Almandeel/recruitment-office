<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'expenses';

    /**
     * Run the migrations.
     * @table expenses
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->float('amount')->nullable();
            $table->text('details')->nullable();
            $table->tinyInteger('status')->nullable()->default('0');
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('safe_id');

            $table->unsignedInteger('year_id');
            $table->index(["year_id"], 'fk_' . $this->tableName . '_years1_idx');
            $table->foreign('year_id', 'fk_' . $this->tableName . '_years1_idx')
                ->references('id')->on('years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index(["warehouse_id"], 'fk_expenses_warehouses1_idx');

            $table->index(["safe_id"], 'fk_expenses_safes1_idx');

            $table->index(["account_id"], 'fk_expenses_accounts1_idx');


            $table->foreign('warehouse_id', 'fk_expenses_warehouses1_idx')
                ->references('id')->on('warehouses')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('account_id', 'fk_expenses_accounts1_idx')
                ->references('id')->on('accounts')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('safe_id', 'fk_expenses_safes1_idx')
                ->references('id')->on('safes')
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
