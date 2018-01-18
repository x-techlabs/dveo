<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaylistParentId extends Migration {

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
            $table->integer('parent_id')->after('level')->unsigned()->nullable();
            $table->integer('sort_order')->after('level')->default(1);
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
            $table->dropColumn('parent_id');
            $table->dropColumn('sort_order');
        });
	}

}
