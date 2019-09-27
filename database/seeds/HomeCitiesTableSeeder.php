<?php

use Illuminate\Database\Seeder;

class HomeCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
           DB::table('home_cities')->delete();
        
        DB::table('home_cities')->insert([

                ['id' => 1 , 'name' => 'New York', 'image' => 'home_city_1461439302.jpg'],
                ['id' => 2 , 'name' => 'Sydney', 'image' => 'home_city_1461439512.jpg'],
                ['id' => 3 , 'name' => 'Hawaii', 'image' => 'home_city_1461439550.jpg'],
                ['id' => 4 , 'name' => 'Paris', 'image' => 'home_city_1461439589.jpg'],
                ['id' => 5 , 'name' => 'Barcelona', 'image' => 'home_city_1461439621.jpg'],
                ['id' => 6 , 'name' => 'Thailand', 'image' => 'home_city_1461439819.jpg'],
                ['id' => 7 , 'name' => 'London', 'image' => 'home_city_1461439854.jpg'],
                ['id' => 8 , 'name' => 'San Francisco', 'image' => 'home_city_1461439887.jpg'],
                ['id' => 9 , 'name' => 'Berlin', 'image' => 'home_city_1461439922.jpg'],
                ['id' => 10 , 'name' => 'Budapest', 'image' => 'home_city_1461439957.jpg']

            ]);
    }
}
