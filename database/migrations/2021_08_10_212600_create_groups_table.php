<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number');
            $table->bigInteger('group_type_id')->unsigned()->nonullable();
            $table->foreign('group_type_id')->references('id')->on('group_types')->onDelete('cascade')->nonullable();
            $table->bigInteger('academic_load_id')->unsigned()->nonullable();
            $table->foreign('academic_load_id')->references('id')->on('academic_loads')->onDelete('cascade')->nonullable();
            $table->bigInteger('course_id')->unsigned()->nonullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->nonullable();
            $table->bigInteger('professor_id')->unsigned()->nullable();
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
        Schema::dropIfExists('groups');
    }
}
