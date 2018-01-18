<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvwebs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//https://laravel.com/docs/4.2/migrations
		Schema::create('tvwebs', function($table) {
            $table->increments('id');
            $table->integer('channel_id');
            $table->string('title');
            $table->text('description');
            $table->string('live_stream_url');
            $table->text('about_us');
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
		Schema::drop('tvwebs');
	}

}
