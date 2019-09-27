<?php

use Illuminate\Database\Seeder;

class PropertyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('property_type')->delete();
        
        DB::table('property_type')->insert([
                      ['name' => 'Apartment','description' => 'Apartment','icon'=>'apartment.png'],
                      ['name' => 'House','description' => 'House','icon'=>'house.png'],
                      ['name' => 'Bed & Break Fast','description' => 'Bed & Break Fast','icon'=>'bed.png'],
                      ['name' => 'Loft','description' => 'Loft','icon'=>'loft.png'],
                      ['name' => 'Townhouse','description' => 'Townhouse','icon'=>'townhouse.png'],
                      ['name' => 'Condominium','description' => 'Condominium','icon'=>'Condominium.png'],
                      ['name' => 'Bungalow','description' => 'Bungalow','icon'=>'bungalow.png'],
                      ['name' => 'Cabin','description' => 'Cabin','icon'=>'cabin.png'],
                      ['name' => 'Villa','description' => 'Villa','icon'=>'villa.png'],
                      ['name' => 'Castle','description' => 'Castle','icon'=>'castle.png'],
                      ['name' => 'Dorm','description' => 'Dorm','icon'=>'dorm.png'],
                      ['name' => 'Treehouse','description' => 'Treehouse','icon'=>'treehouse.png'],
                      ['name' => 'Boat','description' => 'Boat','icon'=>'boat.png'],
                      ['name' => 'Plane','description' => 'Plane','icon'=>'plane.png'],
                      ['name' => 'Camper/RV','description' => 'Camper/RV','icon'=>'camper.png'],
                      ['name' => 'Lgloo','description' => 'Lgloo','icon'=>'igloo.png'],
                      ['name' => 'Lighthouse','description' => 'Lighthouse','icon'=>'lighthouse.png'],
                      ['name' => 'Yurt','description' => 'Yurt','icon'=>'yurt.png'],
                      ['name' => 'Tipi','description' => 'Tipi','icon'=>'tipi.png'],
                      ['name' => 'Cave','description' => 'Cave','icon'=>'cave.png'],
                      ['name' => 'Island','description' => 'Island','icon'=>'island.png'],
                      ['name' => 'Chalet','description' => 'Chalet','icon'=>'chalet.png'],
                      ['name' => 'Earth House','description' => 'Earth House','icon'=>'earthouse.png'],
                      ['name' => 'Hut','description' => 'Hut','icon'=>'hut.png'],
                      ['name' => 'Train','description' => 'Train','icon'=>'train.png'],
                      ['name' => 'Tent','description' => 'Tent','icon'=>'tent.png'],
                      ['name' => 'Other','description' => 'Other','icon'=>'other.png']
            ]);
    }
}
