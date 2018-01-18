<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLevelToTvappPlaylistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tvapp_playlist', function(Blueprint $table)
		{
			//
            $table->integer('level')->after('type');
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
            $table->dropColumn('level');
			//
		});
	}

}
