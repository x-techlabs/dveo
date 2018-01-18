<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReshapeScheduleVideoSchema extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('schedule_video',function(Blueprint $table){  
			$table->dropColumn('video_id');
			$table->dropColumn('type');       
            $table->text('genere')->after('end_date');
            $table->text('url')->after('genere');
            $table->text('video_id_list')->after('url');
    	});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
