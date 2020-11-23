<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'transfers';

    /**
     * Run the migrations.
     * @table transfers
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
            $table->text('details')->nullable();
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->unsignedInteger('year_id');
            $table->index(["year_id"], 'fk_entries_years1_idx');
            $table->foreign('year_id', 'fk_entries_years1_idx')
                ->references('id')->on('years')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            // $table->index(["from_id"], 'fk_transfers_accounts1_idx');

            // $table->index(["to_id"], 'fk_transfers_accounts2_idx');

            // $table->foreign('from_id', 'fk_transfers_accounts1_idx')
            //     ->references('id')->on('accounts')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');

            // $table->foreign('to_id', 'fk_transfers_accounts2_idx')
            //     ->references('id')->on('accounts')
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
