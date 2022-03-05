<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHiringActivitiesTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiring_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('detail_position_activity_id');
            $table->integer('activity_id');
            $table->foreign('detail_position_activity_id')->references('id')->on('detail_position_activities')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('hiring_activities');
    }
}
