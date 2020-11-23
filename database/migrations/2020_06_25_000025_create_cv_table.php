<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cvs';

    /**
     * Run the migrations.
     * @table cv
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 145);
            $table->string('passport', 45);
            $table->boolean('accepted')->default(false);
            $table->tinyInteger('status')->nullable()->default(0);
            $table->float('amount')->default(0);
            $table->date('birth_date')->nullable();
            $table->tinyInteger('gender')->nullable()->default('1');
            $table->integer('children')->nullable()->default(0);
            $table->string('procedure')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->boolean('billed')->default(false);

            $table->string('reference_number')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('phone')->nullable();
            $table->string('qualification')->nullable();
            $table->string('english_speaking_level')->nullable();
            $table->string('experince')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();

            $table->boolean('sewing')->default(false);
            $table->boolean('decor')->default(false);
            $table->boolean('cleaning')->default(false);
            $table->boolean('washing')->default(false);
            $table->boolean('cooking')->default(false);
            $table->boolean('babysitting')->default(false);
            
            $table->string('passport_place_of_issue')->nullable();
            $table->date('passport_issuing_date')->nullable();
            $table->date('passport_expiration_date')->nullable();

            $table->string('contract_period')->nullable();
            $table->integer('contract_salary')->nullable();

            $table->text('bio')->nullable();

            $table->string('photo')->nullable();
            $table->string('passport_photo')->nullable();

            $table->unsignedInteger('profession_id');
            $table->unsignedInteger('contract_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('office_id')->nullable();
            $table->timestamps();

            $table->index(["profession_id"], 'fk_cv_professions_idx');

            $table->index(["contract_id"], 'fk_cv_contracts1_idx');


            $table->foreign('profession_id', 'fk_cv_professions_idx')
                ->references('id')->on('professions')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('contract_id', 'fk_cv_contracts1_idx')
                ->references('id')->on('contracts')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('country_id')
                ->references('id')->on('countries')
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
