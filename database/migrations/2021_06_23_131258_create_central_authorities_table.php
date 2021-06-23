<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentralAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('central_authorities', function (Blueprint $table) {
            $table->id();
            $table->string('position', 120);
            $table->string('firstName', 60);
            $table->string('middleName', 60)->nullable();
            $table->string('lastName', 120);
            $table->string('dui', 20);
            $table->string('nit', 20);
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
        Schema::dropIfExists('central_authorities');
    }
}
