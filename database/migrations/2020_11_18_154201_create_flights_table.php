<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('departure_at')->nullable();
            $table->timestamp('arrival_at')->nullable();
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->string('trip_number')->nullable();
            $table->string('airline_name');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('cv_flight', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('flight_id');
            $table->unsignedBigInteger('cv_id');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('customer_status')->default(0);
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
        Schema::dropIfExists('flights');
    }
}
