<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->tinyInteger('start_method')->unsigned()->nullable();
            $table->tinyInteger('initial_status')->unsigned()->nullable();
            $table->smallInteger('timeout_time')->unsigned()->nullable();
            $table->string('wifi_ssid')->nullable();
            $table->string('wifi_password');
            $table->boolean('wifi_success');
            $table->boolean('has_lights')->default(0);
            $table->boolean('play_sounds')->default(0);
            $table->boolean('exists')->default(0);
            $table->boolean('should_shutdown')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
