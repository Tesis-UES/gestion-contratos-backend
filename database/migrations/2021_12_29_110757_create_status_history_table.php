<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('status_id')->unsigned()->nonullable();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade')->nonullable();
            $table->bigInteger('hiring_request_id')->unsigned()->nonullable();
            $table->foreign('hiring_request_id')->references('id')->on('hiring_requests')->onDelete('cascade')->nonullable();
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('status_history');
    }
}
