<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->enum('role', ['Designer', 'Customer','Constructor','Admin'])->default('Customer');
            $table->enum('loginType', ['standart','facebook', 'google','apple'])->default('standart');
            $table->enum('type', ['Freelancer ','Company']);
            $table->string('adress')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('photoUrl')->nullable();
            $table->string('currency')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
