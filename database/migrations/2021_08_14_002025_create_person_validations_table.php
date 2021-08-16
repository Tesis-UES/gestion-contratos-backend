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
            $table->boolean('dui_readable')->default(false);
            $table->boolean('name_correct')->default(false);
            $table->boolean('address_correct')->default(false);
            $table->boolean('dui_current')->default(false);
            $table->boolean('nit_readable')->default(false);
            $table->boolean('curriculum_readable')->default(false);
            $table->boolean('profesional_title_readable')->default(false);
            $table->boolean('profesional_title_validated')->default(false);
            $table->boolean('bank_account_readable')->default(false);
            $table->boolean('work_permission_readable')->nullable();
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
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
