<?php

use Illuminate\Database\Seeder;

class HostExperienceCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('host_experience_categories')->delete();
    	
        DB::table('host_experience_categories')->insert([
            ['name' => 'Arts & Design'],
            ['name' => 'Fashion'],
            ['name' => 'Entertainment'],
            ['name' => 'Sports'],
            ['name' => 'Wellness'],
            ['name' => 'Nature'],
            ['name' => 'Food & Drink'],
            ['name' => 'Lifestyle'],
            ['name' => 'History'],
            ['name' => 'Music'],
            ['name' => 'Business'],
        	['name' => 'Nightlife'],
        ]);

        DB::table('permissions')->insert([
            ['name' => 'manage_host_experience_categories', 'display_name' => 'Manage Host Experience Categories', 'description' => 'Manage Host Experience Categories'],
            ['name' => 'manage_host_experience_provide_items', 'display_name' => 'Manage Host Experience Provide Items', 'description' => 'Manage Host Experience Provide Items'],
            ['name' => 'manage_host_experience_cities', 'display_name' => 'Manage Host Experience Cities', 'description' => 'Manage Host Experience Cities'],
            ['name' => 'manage_host_experiences', 'display_name' => 'View Host Experiences', 'description' => 'View Host Experiences'],
            ['name' => 'add_host_experiences', 'display_name' => 'Add Host Experiences', 'description' => 'Add Host Experiences'],
            ['name' => 'edit_host_experiences', 'display_name' => 'Edit Host Experiences', 'description' => 'Edit Host Experiences'],
            ['name' => 'delete_host_experiences', 'display_name' => 'Delete Host Experiences', 'description' => 'Delete Host Experiences'],
            ['name' => 'manage_host_experiences_reservation', 'display_name' => 'Manage Host Experiences Reservation', 'description' => 'Manage Host Experiences Reservation'],
            ['name' => 'manage_host_experiences_reviews', 'display_name' => 'Manage Host Experiences Reviews', 'description' => 'Manage Host Experiences Reviews'],
            ['name' => 'manage_host_experiences_inquiries', 'display_name' => 'Manage Host Experiences Inquiries', 'description' => 'Manage Host Experiences Inquiries']
        ]);
    }
}
