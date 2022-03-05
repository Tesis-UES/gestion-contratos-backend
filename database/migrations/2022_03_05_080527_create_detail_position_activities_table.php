<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPositionActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_position_activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('position_id')->unsigned()->nonullable();
            $table->foreign('position_id')->references('id')->on('positions')->nonullable();
            $table->bigInteger('hiring_request_detail_id')->unsigned()->nonullable();
            $table->foreign('hiring_request_detail_id')->references('id')->on('hiring_request_details')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('detail_position_activities');
    }
}
