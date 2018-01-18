<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleVideoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedule_video', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('video_id');
            $table->integer('channel_id');
            $table->string('name');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
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
		Schema::drop('schedule_video');
	}

}
