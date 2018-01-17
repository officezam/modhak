<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Leadsdetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('Leadsdetail', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('leads_id')->nullable();;
		    $table->string('question')->nullable();;
		    $table->string('answer')->nullable();;
		    $table->integer('question_no')->nullable();;
		    $table->string('static_reply')->nullable();
		    $table->string('audio')->nullable();
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
	    Schema::dropIfExists('Leadsdetail');
    }
}
