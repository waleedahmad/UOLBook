<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocietyMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('society_members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->integer('user_id')->unsigned();
            $table->integer('society_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('society_id')->references('id')->on('societies');
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
        Schema::dropIfExists('society_members');
    }
}
