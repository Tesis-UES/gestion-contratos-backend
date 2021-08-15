<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_loads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('school_id')->unsigned()->nonullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->nonullable();
            $table->bigInteger('semester_id')->unsigned()->nonullable();
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade')->nonullable();
            $table->softDeletes();
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
        Schema::dropIfExists('academic_loads');
    }
}
