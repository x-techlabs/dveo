<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLayoutUrlToTvappPlaylist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tvapp_playlist', function(Blueprint $table)
		{
            $table->integer('layout')->after('level');
            $table->text('stream_url')->after('layout');
			//
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tvapp_playlist', function(Blueprint $table)
		{
            $table->dropColumn('layout');
            $table->dropColumn('stream_url');
			//
		});
	}

}
