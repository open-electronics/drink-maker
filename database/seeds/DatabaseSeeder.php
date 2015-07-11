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
        $unguard=[['SET foreign_key_checks=0','SET foreign_key_checks=1'],
            ['PRAGMA foreign_keys=OFF','PRAGMA foreign_keys=ON']];
        $driver=env('DB_CONNECTION')=='sqlite';
        Model::unguard();
        DB::statement($unguard[$driver][0]);
        DB::table('drinks_ingredients')->truncate();
        DB::table('ingredients')->truncate();
        DB::table('drinks')->truncate();
        DB::statement($unguard[$driver][1]);
        //Seed ingredients
        DB::table('ingredients')->insert(['name'=>'Gin','position'=>1,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Campari','position'=>3,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Martini rosso','position'=>6,'stock'=>35]);

        DB::table('ingredients')->insert(['name'=>'Prosecco','position'=>2,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Aperol','position'=>4,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Soda','position'=>7,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Martini dry','position'=>5,'stock'=>35]);
        DB::table('ingredients')->insert(['name'=>'Vodka','position'=>8,'stock'=>35]);


        //Seed drinks
        DB::table('drinks')->insert(['name'=>'Negroni','photo'=>'negroni.jpg']);
        DB::table('drinks')->insert(['name'=>'Spritz','photo'=>'spritz.jpg']);
        DB::table('drinks')->insert(['name'=>'Negroni sbagliato','photo'=>'negronisb.jpg']);
        DB::table('drinks')->insert(['name'=>'Americano','photo'=>'americano.jpg']);
        DB::table('drinks')->insert(['name'=>'Martini dry','photo'=>'dry.jpg']);
        DB::table('drinks')->insert(['name'=>'Vodka martini','photo'=>'vodma.jpg']);

        //Recipes
        $recipes=[
            ['drink_id'=>1,'ingredient_id'=>1,'needed'=>2],//Seed negroni Gin
            ['drink_id'=>1,'ingredient_id'=>2,'needed'=>2],//Seed negroni Campari
            ['drink_id'=>1,'ingredient_id'=>3,'needed'=>2],//Seed negroni Martini rosso
            ['drink_id'=>2,'ingredient_id'=>4,'needed'=>3],//Seed aperol prosecco
            ['drink_id'=>2,'ingredient_id'=>5,'needed'=>2],//Seed aperol aperol
            ['drink_id'=>2,'ingredient_id'=>6,'needed'=>1],//Seed aperol soda
            ['drink_id'=>3,'ingredient_id'=>4,'needed'=>1],//Seed negronisb prosecco
            ['drink_id'=>3,'ingredient_id'=>2,'needed'=>1],//Seed negronisb Campari
            ['drink_id'=>3,'ingredient_id'=>3,'needed'=>1],//Seed negronisb Martini rosso
            ['drink_id'=>4,'ingredient_id'=>2,'needed'=>2],//Seed americano campari
            ['drink_id'=>4,'ingredient_id'=>6,'needed'=>1],//Seed americano soda
            ['drink_id'=>4,'ingredient_id'=>3,'needed'=>2],//Seed americano Martini rosso
            ['drink_id'=>5,'ingredient_id'=>1,'needed'=>1],//Seed dry Gin
            ['drink_id'=>5,'ingredient_id'=>7,'needed'=>1],//Seed dry dry
            ['drink_id'=>6,'ingredient_id'=>7,'needed'=>1],//Seed vodma dry
            ['drink_id'=>6,'ingredient_id'=>8,'needed'=>2],//Seed vodma vodka
        ];

        //Seed recipes
        DB::table('drinks_ingredients')->insert($recipes);

        DB::table('orders')->insert(['drink_id'=>1,'name'=>'ei','status'=>0]);
        DB::table('orders')->insert(['drink_id'=>1,'name'=>'ei','status'=>1]);
        DB::table('orders')->insert(['drink_id'=>1,'name'=>'ei','status'=>2]);
        DB::table('orders')->insert(['drink_id'=>1,'name'=>'ei','status'=>3]);
        DB::table('orders')->insert(['drink_id'=>1,'name'=>'ei','status'=>4]);


        DB::table('settings')->where('id', '1')->insert(['username' => 'User','Password'=>\Illuminate\Support\Facades\Hash::make('password')]);
        Model::reguard();
        // $this->call('UserTableSeeder');
    }

}
