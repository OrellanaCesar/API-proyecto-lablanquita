<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('profile_id');
            $table->string('user_name');
            $table->string('user_password');
            $table->string('user_email')->unique();
            $table->timestamp('user_create_date')->nullable();
            $table->timestamp('user_change_date')->nullable();
            $table->boolean('user_notices');
            $table->rememberToken();
            $table->foreign('profile_id')->references('profile_id')->on('profiles');
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
}
