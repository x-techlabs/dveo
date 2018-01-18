<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortOrderToTvappVideoInPlaylist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tvapp_video_in_playlist', function(Blueprint $table)
		{
			//
            $table->integer('sort_order')->after('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tvapp_video_in_playlist', function(Blueprint $table)
		{
			//
            $table->dropColumn('sort_order');
		});
	}

}
