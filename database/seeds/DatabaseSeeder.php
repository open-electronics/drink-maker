<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
        DB::statement('SET foreign_key_checks=0');
        DB::table('drinks_ingredients')->truncate();
        DB::table('ingredients')->truncate();
        DB::table('drinks')->truncate();
        DB::statement('SET foreign_key_checks=1');
        //Seed ingredients
        DB::insert('INSERT INTO ingredients (ingredient,stock,position ) values("Ice",5,1)');
        DB::insert('INSERT INTO ingredients (ingredient,stock,position) values("Rum",5,3)');
        DB::insert('INSERT INTO ingredients (ingredient,stock,position) values("Vodka",5,4)');
        DB::insert('INSERT INTO ingredients (ingredient,stock,position) values("Gin",5,5)');
        DB::insert('INSERT INTO ingredients (ingredient,stock,position) values("Jack",5,7)');
        //Seed drinks
        $r=rand(3,10);
        for($i=0;$i<$r;$i++){
            DB::insert('INSERT INTO drinks (name) values(?)',['asd'.$i]);
        }
        //Seed relationship
        for($d=1;$d<=$r;$d++) {
            for ($i = 1; $i < 6; $i++) {
                if (rand(0, 1) == 0) {
                    DB::insert('INSERT INTO drinks_ingredients (drink_id,ingredient_id,needed) values(?,?,?)', [$d, $i,rand(1,8)]);
                }
            }
        }
        Model::reguard();
		// $this->call('UserTableSeeder');
	}

}
