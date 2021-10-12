<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nonullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->nonullable();
            $table->string('first_name',120);
            $table->string('middle_name',120);
            $table->string('last_name',120);
            $table->string('know_as',120)->nullable();
            $table->string('civil_status',120);
            $table->string('married_name',120)->nullable();
            $table->date('birth_date');
            $table->string('gender',120);
            $table->string('telephone',120);
            $table->string('alternate_telephone',120)->nullable();
            $table->string('alternate_mail',120)->nullable();
            $table->string('address',250);
            $table->string('city',250)->nullable();;
            $table->string('department',250)->nullable();;
            $table->string('nationality', 120);
            $table->string('work_permission', 250)->nullable();
            $table->string('professional_title',250);
            $table->string('nup')->nullable();;
            $table->string('isss_number')->nullable();;
            $table->string('passport_number')->nullable();
            $table->string('passport')->nullable();
            $table->string('dui_number',120)->nullable();;
            $table->string('dui_text')->nullable();;
            $table->string('reading_signature')->default('Ilegible');
            $table->date('dui_expiration_date')->nullable();
            $table->string('dui',250)->nullable();
            $table->string('nit_number',120)->nullable();;
            $table->string('nit_text')->nullable();;
            $table->string('nit',250)->nullable();
            $table->string('curriculum',250)->nullable();
            $table->string('professional_title_scan',250)->nullable();
            $table->string('bank_account_number',120)->nullable();;
            $table->string('bank_account',250)->nullable();
            $table->string('status',50)->default('Registrando');
            $table->boolean('is_employee')->nullable();
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
        Schema::dropIfExists('people');
    }
}
