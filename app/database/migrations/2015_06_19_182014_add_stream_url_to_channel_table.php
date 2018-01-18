<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStreamUrlToChannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channel', function(Blueprint $table)
		{
            $table->string('stream_url')->after('stream');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channel', function(Blueprint $table)
		{
            $table->dropColumn('stream_url');
		});
	}

}
