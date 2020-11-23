<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'notes';

    /**
     * Run the migrations.
     * @table notes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->increments('id');
            $table->text('content');
            $table->string('noteable_type', 45);
            $table->unsignedInteger('noteable_id')->nullable();
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
