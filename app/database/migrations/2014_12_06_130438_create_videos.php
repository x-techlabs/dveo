<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('video', function($table) {
            $table->increments('id');
            $table->integer('playlist_id');
            $table->integer('channel_id');
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail_name');
            $table->integer('duration');
            $table->string('file_name');
            $table->text('video_format');
            $table->string('job_id');
            $table->integer('encode_status');
            $table->integer('type');
            
            //added
            
            $table->integer('hd_width');
            $table->integer('hd_height');
            $table->integer('hd_file_size');
            $table->integer('hd_video_bitrate');
            $table->string('hd_audio_codec');
            $table->string('hd_video_codec');
            $table->string('hd_mime_type');
            
            $table->string('sd_file_name');
            $table->integer('sd_duration');
            
            $table->integer('sd_width');
            $table->integer('sd_height');
            $table->integer('sd_file_size');
            $table->integer('sd_video_bitrate');
            $table->string('sd_audio_codec');
            $table->string('sd_video_codec');
            $table->string('sd_mime_type');
            
            $table->string('mb_file_name');
            $table->integer('mb_duration');
            
            $table->integer('mb_width');
            $table->integer('mb_height');
            $table->integer('mb_file_size');
            $table->integer('mb_video_bitrate');
            $table->string('mb_audio_codec');
            $table->string('mb_video_codec');
            $table->string('mb_mime_type');
            
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
		Schema::drop('video');
	}

}
