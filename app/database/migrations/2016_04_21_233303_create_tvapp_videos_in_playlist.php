<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvappVideosInPlaylist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tvapp_video_in_playlist', function($table) {
            $table->increments('id');
            $table->integer('video_id');
            $table->integer('tvapp_playlist_id');
            $table->integer('type');
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
		Schema::drop('tvapp_video_in_playlist');
	}

}
