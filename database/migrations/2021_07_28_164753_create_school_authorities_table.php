<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_authorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->date('startPeriod');
            $table->date('endPeriod');
            $table->bigInteger('school_id')->unsigned()->nonullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('school_authorities');
    }
}
