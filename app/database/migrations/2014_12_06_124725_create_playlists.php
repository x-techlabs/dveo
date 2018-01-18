<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylists extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('playlist', function($table) {
            $table->increments('id');
            $table->integer('channel_id');
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail_name');
            $table->integer('duration');
            $table->integer('master_looped');
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
        Schema::drop('playlist');
	}

}
