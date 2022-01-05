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
            $table->string('partida', 50)->nullable();
            $table->string('sub_partida', 50)->nullable();
            $table->enum('journey_type', ['tiempo-completo', 'medio-tiempo', 'cuarto-tiempo', 'tiempo-parcial', 'tiempo-eventual']);
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->bigInteger('faculty_id')->unsigned()->nonullable();
            $table->bigInteger('escalafon_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
            $table->foreign('faculty_id')->references('id')->on('faculties')->nonullable();
            $table->foreign('escalafon_id')->references('id')->on('escalafons')->nonullable();
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
