<?php

use Illuminate\Database\Seeder;

class AmenitiesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amenities_type')->delete();
        
        DB::table('amenities_type')->insert([
                ['name' => 'Common Amenities','description' => ''],
                ['name' => 'Additional Amenities','description' => ''],
                ['name' => 'Special Features','description' => 'Features of your listing for guests with specific needs.'],
                ['name' => 'Home Safety','description' => 'Smoke and carbon monoxide detectors are strongly encouraged for all listings.'],
            ]);
    }
}
