<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvappPlatforms extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('tvapp_platforms', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
        });

        Schema::create('tvapp_playlist_platforms', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('tvapp_playlist_id')->unsigned();
            $table->integer('tvapp_platform_id')->unsigned();
        });

        Schema::table('tvapp_playlist_platforms', function(Blueprint $table) {
            $table->foreign('tvapp_playlist_id')->references('id')->on('tvapp_playlist');
            $table->foreign('tvapp_platform_id')->references('id')->on('tvapp_platforms');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('tvapp_playlist_platforms');
		Schema::drop('tvapp_platforms');
	}

}
