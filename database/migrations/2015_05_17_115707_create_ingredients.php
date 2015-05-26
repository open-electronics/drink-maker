<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredients extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //select d.name,i.name,needed,stock from drinks as d LEFT JOIN drinks_ingredients as di ON d.id=di.drink_id LEFT JOIN ingredients as i ON di.ingredient_id=i.id;
//        "select d.id,d.name,i.id,i.name,i.position,needed,stock from drinks as d LEFT JOIN drinks_ingredients as di ON d.id=di.drink_id LEFT JOIN ingredients as i ON di.ingredient_id=i.id WHERE d.id=1 ORDER BY i.position ASC"
        Schema::create('ingredients', function(Blueprint $table)
		{
			$table->increments('id');
            $table->char('ingredient',50);
            $table->decimal('stock',3,1);
            $table->tinyInteger('position')->nullable();
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
		Schema::drop('ingredients');
	}

}
