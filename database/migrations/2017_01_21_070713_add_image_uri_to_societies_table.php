<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageUriToSocietiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('societies', function (Blueprint $table) {
            $table->string('image_uri')->after('user_id');
            $table->boolean('verified')->after('image_uri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('societies', function (Blueprint $table) {
            $table->dropColumn('image_uri');
            $table->dropColumn('verified');
        });
    }
}
