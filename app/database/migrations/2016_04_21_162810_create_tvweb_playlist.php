<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvwebPlaylist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//https://laravel.com/docs/4.2/migrations
	    Schema::create('tvweb_playlist', function($table) {
            $table->increments('id');
            $table->integer('channel_id');
            $table->integer('tvweb_id');
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail_name');
            $table->integer('duration');
            $table->integer('master_looped');
            $table->integer('type');
            $table->string('status');
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
		Schema::drop('tvweb_playlist');
	}

}
