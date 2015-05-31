<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('drink_id')->unsigned();
            $table->foreign('drink_id')->references('id')->on('drinks')->onDelete('cascade');
            $table->char('name',30);
            $table->tinyInteger('status')->unsigned();//0=not seen, 1=approved,2=working 3=completed, 4=deleted
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
