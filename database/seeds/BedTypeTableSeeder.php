<?php

use Illuminate\Database\Seeder;

class BedTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bed_type')->delete();
        
        DB::table('bed_type')->insert([
                ['name' => 'Airbed'],
                ['name' => 'Futon'],
                ['name' => 'Pull-out Sofa'],
                ['name' => 'Couch'],
                ['name' => 'Real Bed'],
            ]);
    }
}
