<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('membertype_id');
	        $table->string('name')->nullable();
	        $table->string('first_name')->nullable();
	        $table->string('last_name')->nullable();
	        $table->string('address')->nullable();
	        $table->string('city')->nullable();
	        $table->string('state')->nullable();
	        $table->string('country')->nullable();
	        $table->string('zip_code')->nullable();
	        $table->string('phone')->nullable();
	        $table->string('email')->nullable();
	        $table->string('type')->nullable();
	        $table->string('status')->nullable();
	        $table->integer('leads_id')->nullable();
	        $table->integer('question_id')->nullable();
	        $table->string('last_answer')->nullable();
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
        Schema::dropIfExists('members');
    }
}
