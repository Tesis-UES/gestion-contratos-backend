<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmployeeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_employee_types', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('employee_type_id');
            $table->foreign('employee_id')->references('id')->on('employees')->nonullable();
            $table->foreign('employee_type_id')->references('id')->on('employee_types')->nonullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_employee_types');
    }
}
