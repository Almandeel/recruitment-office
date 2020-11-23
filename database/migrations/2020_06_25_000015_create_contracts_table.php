<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'contracts';

    /**
     * Run the migrations.
     * @table contracts
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('visa')->nullable();
            $table->integer('marketing_ratio')->nullable();
            $table->text('details')->nullable();
            $table->string('destination')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->string('date_arrival')->nullable();
            $table->unsignedInteger('profession_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('marketer_id')->nullable();
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('customer_id');
            $table->double('amount')->nullable()->default('0');
            $table->integer('ex_date')->nullable()->default(90);
            $table->date('start_date')->nullable();
            $table->boolean('status')->nullable()->default('0');
            $table->timestamps();

            $table->index(["customer_id"], 'fk_contracts_customer1_idx');
            $table->index(["marketer_id"], 'fk_contracts_marketer1_idx');

            $table->index(["country_id"], 'fk_contracts_countries1_idx');

            $table->index(["country_id"], 'fk_contracts_professions1_idx');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('profession_id')
                ->references('id')->on('professions')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('marketer_id', 'fk_contracts_marketer1_idx')
                ->references('id')->on('marketers')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('country_id', 'fk_contracts_countries1_idx')
                ->references('id')->on('countries')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id')
            ->references('id')->on('users')
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
