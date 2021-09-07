<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaySchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stay_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('semester_id')->unsigned()->nonullable();
            $table->bigInteger('professor_id')->unsigned()->nonullable();
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade')->nonullable();
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('stay_schedules');
    }
}
