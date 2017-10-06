<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMosqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mosque', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('mosque_name');
	        $table->string('city');
	        $table->dateTime('date');
	        $table->dateTime('fajar_time');
	        $table->dateTime('zuhar_time');
	        $table->dateTime('asar_time');
	        $table->dateTime('magrib_time');
	        $table->dateTime('esha_time');
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
        Schema::dropIfExists('mosque');
    }
}
