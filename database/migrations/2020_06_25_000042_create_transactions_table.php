<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'transactions';

    /**
     * Run the migrations.
     * @table transactions
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('month')->length(20)->nullable();
            $table->tinyInteger('type')->nullable()->default('0');
            $table->tinyInteger('status')->nullable()->default('0');
            // $table->unsignedInteger('entry_id');
            $table->float('amount')->nullable();
            $table->text('details')->nullable();
            // $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('year_id');
            $table->timestamps();

            $table->index(["employee_id"], 'fk_transactions_employees1_idx');

            $table->index(["year_id"], 'fk_' . $this->tableName . '_years1_idx');
            $table->foreign('year_id', 'fk_' . $this->tableName . '_years1_idx')
                ->references('id')->on('years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('employee_id', 'fk_transactions_employees1_idx')
                ->references('id')->on('employees')
                ->onDelete('no action')
                ->onUpdate('no action');

            // $table->index(["user_id"], 'fk_transactions_users1_idx');

            // $table->index(["entry_id"], 'fk_transactions_entries1_idx');


            // $table->foreign('entry_id', 'fk_transactions_entries1_idx')
            //     ->references('id')->on('entries')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');

            // $table->foreign('user_id', 'fk_transactions_users1_idx')
            //     ->references('id')->on('users')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');
        });

        Schema::enableForeignKeyConstraints();

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
