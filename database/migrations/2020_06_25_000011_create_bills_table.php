<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bills';

    /**
     * Run the migrations.
     * @table bills
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->text('notes')->nullable();
            $table->float('amount')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default('0');
            $table->float('riyal_per_dollar')->nullable()->default(0);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('office_id')->nullable();
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
