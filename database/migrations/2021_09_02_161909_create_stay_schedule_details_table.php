<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStayScheduleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stay_schedule_details', function (Blueprint $table) {
            $table->id();
            $table->string('day')->nonullable();
            $table->time('start_time')->nonullable();
            $table->time('finish_time')->nonullable();
            $table->bigInteger('stay_schedule_id')->unsigned()->nonullable();
            $table->foreign('stay_schedule_id')->references('id')->on('stay_schedules')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('stay_schedule_details');
    }
}
