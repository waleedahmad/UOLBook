<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('file_uri');
            $table->string('caption');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
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
        Schema::dropIfExists('uploads');
    }
}
