<?php

use Illuminate\Database\Seeder;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slider')->delete();
    	
        DB::table('slider')->insert([
        	['image' => 'slider_1.png',	'order' => '0','status' => 'Active',"front_end"=>"Homepage"],
  			['image' => 'slider_2.png',	'order' => '1','status' => 'Active',"front_end"=>"Homepage"],
  			['image' => 'slider_3.png',	'order' => '2','status' => 'Active',"front_end"=>"Adminpage"],
        ]);
    }
}
