<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('approved');
            $table->boolean('no_effect')->default(false);
            $table->date('agreed_on');
            $table->string('file_uri');
            $table->bigInteger('hiring_request_id')->unsigned()->nonullable();
            $table->foreign('hiring_request_id')->references('id')->on('hiring_requests')->nonullable();
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
        Schema::dropIfExists('agreements');
    }
}
