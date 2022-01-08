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
            $table->integer('hiring_request_detail_id');
            $table->integer('activity_id');
            $table->foreign('hiring_request_detail_id')->references('id')->on('positions')->nonullable();
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
