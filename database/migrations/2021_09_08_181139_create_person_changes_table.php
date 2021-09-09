<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_changes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('person_id')->unsigned()->nonullable();
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade')->nonullable();
            $table->string('change');
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
        Schema::dropIfExists('person_changes');
    }
}
