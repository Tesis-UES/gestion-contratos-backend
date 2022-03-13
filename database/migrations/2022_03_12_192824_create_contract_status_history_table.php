<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_status_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contract_status_id')->unsigned()->nonullable();
            $table->foreign('contract_status_id')->references('id')->on('contract_status')->onDelete('cascade')->nonullable();
            $table->bigInteger('hiring_request_detail_id')->unsigned()->nonullable();
            $table->foreign('hiring_request_detail_id')->references('id')->on('hiring_request_details')->onDelete('cascade')->nonullable();
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
        Schema::dropIfExists('contract_status_histories');
    }
}
