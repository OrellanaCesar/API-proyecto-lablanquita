<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('profiles', function (Blueprint $table) {
    		$table->id('profile_id');
    		$table->string('profile_name');
    		$table->timestamp('profile_create_date')->nullable();
    		$table->timestamp('profile_change_date')->nullable();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::dropIfExists('profiles');
    }
}
