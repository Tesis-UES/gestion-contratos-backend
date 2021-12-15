<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->nonullable();
            $table->string('year')->nonullable();
            $table->integer('next_code')->nonullable()->default(1);
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
        Schema::dropIfExists('request_codes');
    }
}
