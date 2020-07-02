<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {

            $table->unsignedBigInteger('twitch_id')->change();


            $table->unique('twitch_id', 'unique_twitch_id');
            $table->unique('email', 'unique_email');
            $table->index('twitch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->bigInteger('twitch_id')->change();

            $table->dropIndex('unique_email');
            $table->dropIndex('users_twitch_id_uindex');
        });
    }
}
