<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'advances';

    /**
     * Run the migrations.
     * @table advances
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('notes')->nullable();
            $table->float('amount')->nullable()->default('0');
            $table->tinyInteger('status')->default('0');
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('office_id');
            $table->timestamps();
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
