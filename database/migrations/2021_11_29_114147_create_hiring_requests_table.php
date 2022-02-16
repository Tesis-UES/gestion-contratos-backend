<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHiringRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiring_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code', 120)->nonullable()->unique();
            $table->string('request_status', 10)->nonullable();
            $table->enum('modality', ['Modalidad Presencial', 'Modalidad en Linea']);
            $table->longText('message')->nonullable();
            $table->string('fileName')->nullable();
            $table->bigInteger('contract_type_id')->unsigned()->nonullable();
            $table->foreign('contract_type_id')->references('id')->on('contract_types')->onDelete('cascade')->nonullable();
            $table->bigInteger('school_id')->unsigned()->nonullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')->nonullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hiring_requests');
    }
}
