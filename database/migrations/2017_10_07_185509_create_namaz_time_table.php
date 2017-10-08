<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamazTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namaz_time', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_id');
            $table->dateTime('date');
            $table->dateTime('fajar');
            $table->dateTime('zuhar')->nullable();
            $table->dateTime('jumma')->nullable();
            $table->dateTime('asar');
            $table->dateTime('maghrib');
            $table->dateTime('esha');
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
        Schema::dropIfExists('namaz_time');
    }
}
