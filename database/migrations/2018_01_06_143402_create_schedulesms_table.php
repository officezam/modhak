<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedulesms', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('membertype_id');
	        $table->string('type')->nullable();
	        $table->string('sms')->nullable();
	        $table->dateTime('dateandtime')->nullable();
	        $table->string('status')->nullable();
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
        Schema::dropIfExists('schedulesms');
    }
}
