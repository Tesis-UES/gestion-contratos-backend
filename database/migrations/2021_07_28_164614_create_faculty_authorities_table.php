<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_authorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->date('startPeriod');
            $table->date('endPeriod');
            $table->bigInteger('faculty_id')->unsigned()->nonullable();
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade')->nonullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('faculty_authorities');
    }
}
