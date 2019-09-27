<?php

use Illuminate\Database\Seeder;

class BottomSliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bottom_slider')->delete();
    	
        DB::table('bottom_slider')->insert([
        	['image' => 'banner_inn.png','title'=>'Belong anywhere','description'=>'See how Makent hosts create a sense of belonging around the world ','order' => '0','status' => 'Active'],
  			['image' => 'banner2_inn.png','title'=>'Introducing the Bélo ','description'=>'The story of a symbol of belonging. ','order' => '1','status' => 'Active'],
  			['image' => 'banner3_inn.png','title'=>'Your Home','description'=>'See how Makent hosts create a sense of belonging around the world ','order' => '2','status' => 'Active'],
        ]);
    }
}
