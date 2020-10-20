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
            $table->id('userid');
            $table->unsignedBigInteger('profileid');
            $table->string('username');
            $table->string('userpassword');
            $table->string('useremail')->unique();
            $table->timestamp('usercreatedate')->nullable();
            $table->timestamp('userchangedate')->nullable();
            $table->timestamp('userlowdate')->nullable();
            $table->boolean('usernotices');
            $table->rememberToken();
            $table->foreign('profileid')->references('profileid')->on('profiles');
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
