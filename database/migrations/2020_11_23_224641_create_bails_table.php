<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 10)->default('trail');
            $table->date('trail_date');
            $table->float('amount')->default(0);
            $table->date('bail_date')->nullable()->default(now());
            $table->text('notes')->nullable();
            $table->tinyInteger('trail_period');
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('cv_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('x_customer_id');
            $table->unsignedInteger('x_contract_id');
            $table->unsignedInteger('user_id');

            $table->index('contract_id');
            $table->index('cv_id');
            $table->index('customer_id');
            $table->index('x_contract_id');
            $table->index('x_customer_id');

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('no action');
            $table->foreign('cv_id')->references('id')->on('cvs')->onDelete('no action');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('no action');
            $table->foreign('x_contract_id')->references('id')->on('contracts')->onDelete('no action');
            $table->foreign('x_customer_id')->references('id')->on('customers')->onDelete('no action');

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
        Schema::dropIfExists('bails');
    }
}
