<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseToClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->integer('course_id')->unsigned()->after('teacher_id');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->dropColumn('subject_name');
            $table->dropColumn('subject_code');
            $table->dropColumn('subject_semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('course_id');
            $table->string('subject_name');
            $table->string('subject_code');
            $table->integer('subject_semester');
        });
    }
}
