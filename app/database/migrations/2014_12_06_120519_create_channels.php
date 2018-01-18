<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannels extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('channel', function($table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('title');
            $table->string('stream');
            $table->string('format');
            $table->string('timezone');
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
        Schema::drop('channel');
	}

}
