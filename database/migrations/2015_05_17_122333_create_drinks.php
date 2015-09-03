<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrinks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('drinks', function(Blueprint $table)
		{
			$table->increments('id');
            $table->char('name',50);
            $table->smallInteger('volume')->unsigned();
            $table->string('photo')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('drinks');
	}

}
