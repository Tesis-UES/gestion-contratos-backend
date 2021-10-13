<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('position_id');
            $table->integer('activity_id');
            $table->foreign('position_id')->references('id')->on('positions')->nonullable();
            $table->foreign('activity_id')->references('id')->on('activities')->nonullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('position_activities');
    }
}
