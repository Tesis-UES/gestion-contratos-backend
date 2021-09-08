<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStayScheduleActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stay_schedule_activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stay_schedule_id')->unsigned()->nullable();
            $table->bigInteger('activity_id')->unsigned()->nullable();
            $table->foreign('stay_schedule_id')->references('id')->on('stay_schedules')->onDelete('cascade')->nonullable();
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('stay_schedule_activities');
    }
}
