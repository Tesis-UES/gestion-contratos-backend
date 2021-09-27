<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_validations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
            //Validaciones Corrependientes al DUI
            $table->boolean('dui_readable')->default(false)->nullable();
            $table->boolean('dui_name')->default(false)->nullable();
            $table->boolean('dui_number')->default(false)->nullable();
            $table->boolean('dui_profession')->default(false)->nullable();
            $table->boolean('dui_civil_status')->default(false)->nullable();
            $table->boolean('dui_birth_date')->default(false)->nullable();
            $table->boolean('dui_unexpired')->default(false)->nullable();
            $table->boolean('dui_address')->default(false)->nullable();
            //Validaciones Correspondientes al NIT
            $table->boolean('nit_readable')->default(false)->nullable();
            $table->boolean('nit_name')->default(false)->nullable(); 
            $table->boolean('nit_number')->default(false)->nullable();
            //Validaciones Correspondientes a la cuenta de Banco
            $table->boolean('bank_readable')->default(false)->nullable();
            $table->boolean('bank_number')->default(false)->nullable(); 
            //Validaciones Correspondientes al curriculum
            $table->boolean('curriculum_readable')->default(false)->nullable();
            //Validaciones Correspondientes al Permiso de trabajo de otra facultad
            $table->boolean('work_permission_readable')->default(false)->nullable();
            //Validaciones Correspondientes al Pasaporte
            $table->boolean('passport_readable')->default(false)->nullable();
            $table->boolean('passport_name')->default(false)->nullable();
            $table->boolean('passport_number')->default(false)->nullable();
            //Validaciones Correspondientes al titulo
            $table->boolean('title_readable')->default(false)->nullable();
            //Nacional
            $table->boolean('title_mined')->default(false)->nullable();
            //Internacional
            $table->boolean('title_apostilled')->default(false)->nullable();
            $table->boolean('title_apostilled_redeable')->default(false)->nullable();
            $table->boolean('title_authentic')->default(false)->nullable();
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
        Schema::dropIfExists('person_validations');
    }
}
