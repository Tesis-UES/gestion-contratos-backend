<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHiringRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiring_request_details', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('finish_date');
            $table->string('schedule_file');
            $table->string('position')->nullable();
            $table->string('goal')->nullable();
            $table->integer('work_months')->nullable();
            $table->float('salariy_percentage')->nullable();
            $table->float('hourly_rate')->nullable();
            $table->integer('work_weeks')->nullable();
            $table->integer('weekly_hours')->nullable();
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->nonullable();
            $table->bigInteger('stay_schedule_id')->unsigned()->nullable();
            $table->foreign('stay_schedule_id')->references('id')->on('stay_schedules')->nonullable();
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
        Schema::dropIfExists('hiring_request_details');
    }
}
