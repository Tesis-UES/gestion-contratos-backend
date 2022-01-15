<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHiringGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiring_groups', function (Blueprint $table) {
            $table->id();
            $table->float('hourly_rate')->nullable();
            $table->float('work_weeks')->nullable();
            $table->float('weekly_hours')->nullable();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->nonullable();
            $table->bigInteger('hiring_request_detail_id')->unsigned()->nullable();
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
        Schema::dropIfExists('hiring_groups');
    }
}
