<?php

use Illuminate\Database\Seeder;

class HelpSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('help_subcategory')->delete();

        DB::table('help_subcategory')->insert([
            ['id' => '1','category_id' => '1','name' => 'How it Works','description' => 'How it Works','status' => 'Active'],
            ['id' => '2','category_id' => '1','name' => 'How to Travel','description' => 'How to Travel','status' => 'Active'],
            ['id' => '3','category_id' => '1','name' => 'How to Host','description' => 'How to Host','status' => 'Active'],
            ['id' => '4','category_id' => '2','name' => 'Language & Currency','description' => 'Language & Currency','status' => 'Active'],
            ['id' => '5','category_id' => '3','name' => 'Deciding to Host','description' => 'Deciding to Host','status' => 'Active'],
            ['id' => '6','category_id' => '3','name' => 'Your Listings','description' => 'Your Listings','status' => 'Active'],
            ['id' => '7','category_id' => '3','name' => 'Your Reservations','description' => 'Your Reservations','status' => 'Active'],
            ['id' => '8','category_id' => '3','name' => 'Getting Paid','description' => 'Getting Paid','status' => 'Active'],
            ['id' => '9','category_id' => '4','name' => 'Finding a Place','description' => 'Finding a Place','status' => 'Active'],
            ['id' => '10','category_id' => '4','name' => 'Booking a Place','description' => 'Booking a Place','status' => 'Active'],
            ['id' => '11','category_id' => '4','name' => 'Paying','description' => 'Paying','status' => 'Active'],
            ['id' => '12','category_id' => '4','name' => 'Your Trip','description' => 'Your Trip','status' => 'Active'],
            ['id' => '13','category_id' => '2','name' => 'Sign Up','description' => 'Sign Up','status' => 'Active'],
            ['id' => '14','category_id' => '2','name' => 'Manage Your Profile','description' => 'Manage Your Profile','status' => 'Active'],
            ['id' => '15','category_id' => '2','name' => 'Security & Password','description' => 'Security & Password','status' => 'Active'],
            /*HostExperiencePHPCommentStart*/
            ['id' => '16','category_id' => '6','name' => 'Quality standards','description' => 'Quality standards','status' => 'Active'],
            /*HostExperiencePHPCommentEnd*/
        ]);
    }
}
