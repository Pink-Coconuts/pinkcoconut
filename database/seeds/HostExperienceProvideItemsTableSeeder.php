<?php

use Illuminate\Database\Seeder;

class HostExperienceProvideItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('host_experience_provide_items')->delete();
    	
        DB::table('host_experience_provide_items')->insert([
            ['name' => 'Meal',           'image' => 'icon_meal.png'],
            ['name' => 'Drinks',         'image' => 'icon_drinks.png'],
            ['name' => 'Accommodations', 'image' => 'icon_accommodations.png'],
            ['name' => 'Tickets',        'image' => 'icon_tickets.png'],
            ['name' => 'Transportation', 'image' => 'icon_transportation.png'],
            ['name' => 'Equipment',      'image' => 'icon_equipment.png'],
            ['name' => 'Snacks',         'image' => 'icon_meal.png'],
        ]);
    }
}
