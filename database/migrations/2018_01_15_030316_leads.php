<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Leads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('leads', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name');
		    $table->string('type')->nullable();
		    $table->string('description')->nullable();
		    $table->string('status')->nullable();
		    $table->string('wrong_input_reply')->nullable();
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
	    Schema::dropIfExists('leads');
    }
}
