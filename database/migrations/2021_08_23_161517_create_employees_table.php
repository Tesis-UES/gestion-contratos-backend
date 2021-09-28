<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->enum('journey_type', ['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo']);
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->bigInteger('faculty_id')->unsigned()->nonullable();
            $table->bigInteger('escalafon_id')->unsigned()->nonullable();
            $table->bigInteger('employee_type_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
            $table->foreign('faculty_id')->references('id')->on('faculties')->nonullable();
            $table->foreign('escalafon_id')->references('id')->on('escalafons')->nonullable();
            $table->foreign('employee_type_id')->references('id')->on('employee_types')->nonullable();
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
        Schema::dropIfExists('employees');
    }
}
