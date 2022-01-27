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
            $table->bigInteger('people_id')->unsigned()->nullable();
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
            $table->enum('status', ['SDA','DA','DASC'])->default('SDA');
            $table->enum('modality', ['Presencial','En Linea'])->default('SDA');//SDA (Sin Docente Asignado), DA (Docente Asignado), DASC (Docente Asignado en Solicitd de Contrato)
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
